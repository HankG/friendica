<?php

// Copyright (C) 2010-2024, the Friendica project
// SPDX-FileCopyrightText: 2010-2024 the Friendica project
//
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace Friendica\Federation\Entity;

use DateTimeImmutable;

/**
 * @property-read int               $targetServerId
 * @property-read int               $postUriId
 * @property-read DateTimeImmutable $created
 * @property-read string            $command         One of the Protocol\Delivery command constant values
 * @property-read int               $targetContactId
 * @property-read int               $senderUserId
 * @property-read int               $failed          Number of delivery failures for this post and target server
 */
final class DeliveryQueueItem extends \Friendica\BaseEntity
{
	/** @var int */
	protected $targetServerId;
	/** @var int */
	protected $postUriId;
	/** @var DateTimeImmutable */
	protected $created;
	/** @var string */
	protected $command;
	/** @var int */
	protected $targetContactId;
	/** @var int */
	protected $senderUserId;
	/** @var int */
	protected $failed;

	public function __construct(int $targetServerId, int $postUriId, DateTimeImmutable $created, string $command, int $targetContactId, int $senderUserId, int $failed = 0)
	{
		$this->targetServerId  = $targetServerId;
		$this->postUriId       = $postUriId;
		$this->created         = $created;
		$this->command         = $command;
		$this->targetContactId = $targetContactId;
		$this->senderUserId    = $senderUserId;
		$this->failed          = $failed;
	}
}
