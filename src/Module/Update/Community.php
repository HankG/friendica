<?php

/* Copyright (C) 2010-2024, the Friendica project
 * SPDX-FileCopyrightText: 2010-2024 the Friendica project
 *
 * SPDX-License-Identifier: AGPL-3.0-or-later
 *
 * See update_profile.php for documentation
 */

namespace Friendica\Module\Update;

use Friendica\Content\Conversation;
use Friendica\Core\System;
use Friendica\DI;
use Friendica\Module\Conversation\Community as CommunityModule;

/**
 * Asynchronous update module for the community page
 *
 * @package Friendica\Module\Update
 */
class Community extends CommunityModule
{
	protected function rawContent(array $request = [])
	{
		$this->parseRequest($request);

		$o = '';
		if ($this->update || $this->force) {
			$o = DI::conversation()->render($this->getCommunityItems(), Conversation::MODE_COMMUNITY, true, false, 'commented', DI::userSession()->getLocalUserId());
		}

		System::htmlUpdateExit($o);
	}
}
