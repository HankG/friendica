<?php

// Copyright (C) 2010-2024, the Friendica project
// SPDX-FileCopyrightText: 2010-2024 the Friendica project
//
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace Friendica\Module\Api\Friendica\Circle;

use Friendica\Database\DBA;
use Friendica\Model\Contact;
use Friendica\Model\Circle;
use Friendica\Module\BaseApi;
use Friendica\Network\HTTPException\BadRequestException;

/**
 * API endpoint: /api/friendica/circle_update
 * API endpoint: /api/friendica/group_update
 */
class Update extends BaseApi
{
	protected function post(array $request = [])
	{
		$this->checkAllowedScope(BaseApi::SCOPE_WRITE);
		$uid = BaseApi::getCurrentUserID();

		// params
		$gid   = $this->getRequestValue($request, 'gid', 0);
		$name  = $this->getRequestValue($request, 'name', '');
		$json  = json_decode($request['json'], true);
		$users = $json['user'];

		// error if no name specified
		if (!$name) {
			throw new BadRequestException('circle name not specified');
		}

		// error if no gid specified
		if (!$gid) {
			throw new BadRequestException('gid not specified');
		}

		// remove members
		$members = Contact\Circle::getById($gid);
		foreach ($members as $member) {
			$cid = $member['id'];
			foreach ($users as $user) {
				$found = $user['cid'] == $cid;
			}
			if (!isset($found) || !$found) {
				$gid = Circle::getIdByName($uid, $name);
				Circle::removeMember($gid, $cid);
			}
		}

		// add members
		$erroraddinguser = false;
		$errorusers      = [];
		foreach ($users as $user) {
			$cid = $user['cid'];

			if (DBA::exists('contact', ['id' => $cid, 'uid' => $uid])) {
				Circle::addMember($gid, $cid);
			} else {
				$erroraddinguser = true;
				$errorusers[]    = $cid;
			}
		}

		// return success message incl. missing users in array
		$status  = ($erroraddinguser ? 'missing user' : 'ok');
		$success = ['success' => true, 'gid' => $gid, 'name' => $name, 'status' => $status, 'wrong users' => $errorusers];
		$this->response->addFormattedContent('group_update', ['$result' => $success], $this->parameters['extension'] ?? null);
	}
}
