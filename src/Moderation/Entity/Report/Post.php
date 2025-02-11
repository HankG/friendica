<?php

// Copyright (C) 2010-2024, the Friendica project
// SPDX-FileCopyrightText: 2010-2024 the Friendica project
//
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace Friendica\Moderation\Entity\Report;

/**
 * @property-read int $uriId URI Id of the reported post
 * @property-read int $status One of STATUS_*
 */
final class Post extends \Friendica\BaseEntity
{
	const STATUS_NO_ACTION = 0;
	const STATUS_UNLISTED  = 1;
	const STATUS_DELETED   = 2;

	/** @var int */
	protected $uriId;
	/** @var int|null */
	protected $status;

	public function __construct(int $uriId, int $status = self::STATUS_NO_ACTION)
	{
		$this->uriId  = $uriId;
		$this->status = $status;
	}
}
