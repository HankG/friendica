<?php

// Copyright (C) 2010-2024, the Friendica project
// SPDX-FileCopyrightText: 2010-2024 the Friendica project
//
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace Friendica\Federation\Repository;

use Friendica\Database\Database;
use Friendica\Federation\Factory;
use Friendica\Federation\Entity;
use Psr\Log\LoggerInterface;

class GServer extends \Friendica\BaseRepository
{
	protected static $table_name = 'gserver';

	public function __construct(Database $database, LoggerInterface $logger, Factory\GServer $factory)
	{
		parent::__construct($database, $logger, $factory);
	}

	/**
	 * @param int $gsid
	 * @return Entity\GServer
	 * @throws \Friendica\Network\HTTPException\NotFoundException
	 */
	public function selectOneById(int $gsid): Entity\GServer
	{
		return $this->_selectOne(['id' => $gsid]);
	}
}
