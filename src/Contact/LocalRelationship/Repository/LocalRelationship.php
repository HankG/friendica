<?php

// Copyright (C) 2010-2024, the Friendica project
// SPDX-FileCopyrightText: 2010-2024 the Friendica project
//
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace Friendica\Contact\LocalRelationship\Repository;

use Friendica\Contact\LocalRelationship\Entity;
use Friendica\Contact\LocalRelationship\Exception;
use Friendica\Contact\LocalRelationship\Factory;
use Friendica\Database\Database;
use Friendica\Network\HTTPException;
use Psr\Log\LoggerInterface;

class LocalRelationship extends \Friendica\BaseRepository
{
	protected static $table_name = 'user-contact';

	/** @var Factory\LocalRelationship */
	protected $factory;

	public function __construct(Database $database, LoggerInterface $logger, Factory\LocalRelationship $factory)
	{
		parent::__construct($database, $logger, $factory);
	}

	/**
	 * @param int $uid
	 * @param int $cid
	 * @return Entity\LocalRelationship
	 * @throws HTTPException\NotFoundException
	 */
	public function selectForUserContact(int $uid, int $cid): Entity\LocalRelationship
	{
		return $this->_selectOne(['uid' => $uid, 'cid' => $cid]);
	}

	/**
	 * Returns the existing local relationship between a user and a public contact or a default
	 * relationship if it doesn't.
	 *
	 * @param int $uid
	 * @param int $cid
	 * @return Entity\LocalRelationship
	 * @throws HTTPException\NotFoundException
	 */
	public function getForUserContact(int $uid, int $cid): Entity\LocalRelationship
	{
		try {
			return $this->selectForUserContact($uid, $cid);
		} catch (HTTPException\NotFoundException $e) {
			return $this->factory->createFromTableRow(['uid' => $uid, 'cid' => $cid]);
		}
	}

	/**
	 * @param int $uid
	 * @param int $cid
	 * @return bool
	 * @throws \Exception
	 */
	public function existsForUserContact(int $uid, int $cid): bool
	{
		return $this->exists(['uid' => $uid, 'cid' => $cid]);
	}

	/**
	 * Converts a given local relationship into a DB compatible row array
	 *
	 * @param Entity\LocalRelationship $localRelationship
	 *
	 * @return array
	 */
	protected function convertToTableRow(Entity\LocalRelationship $localRelationship): array
	{
		return [
			'uid'                       => $localRelationship->userId,
			'cid'                       => $localRelationship->contactId,
			'uri-id'                    => $localRelationship->uriId,
			'blocked'                   => $localRelationship->blocked,
			'ignored'                   => $localRelationship->ignored,
			'collapsed'                 => $localRelationship->collapsed,
			'pending'                   => $localRelationship->pending,
			'rel'                       => $localRelationship->rel,
			'info'                      => $localRelationship->info,
			'notify_new_posts'          => $localRelationship->notifyNewPosts,
			'remote_self'               => $localRelationship->remoteSelf,
			'fetch_further_information' => $localRelationship->fetchFurtherInformation,
			'ffi_keyword_denylist'      => $localRelationship->ffiKeywordDenylist,
			'hub-verify'                => $localRelationship->hubVerify,
			'protocol'                  => $localRelationship->protocol,
			'rating'                    => $localRelationship->rating,
			'priority'                  => $localRelationship->priority,
		];
	}

	/**
	 * @param Entity\LocalRelationship $localRelationship
	 *
	 * @return Entity\LocalRelationship
	 *
	 * @throws Exception\LocalRelationshipPersistenceException In case the underlying storage cannot save the LocalRelationship
	 */
	public function save(Entity\LocalRelationship $localRelationship): Entity\LocalRelationship
	{
		try {
			$fields = $this->convertToTableRow($localRelationship);

			$this->db->insert(self::$table_name, $fields, Database::INSERT_UPDATE);

			return $localRelationship;
		} catch (\Exception $exception) {
			throw new Exception\LocalRelationshipPersistenceException(sprintf('Cannot insert/update the local relationship %d for user %d', $localRelationship->contactId, $localRelationship->userId), $exception);
		}
	}
}
