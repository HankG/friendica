<?php

// Copyright (C) 2010-2024, the Friendica project
// SPDX-FileCopyrightText: 2010-2024 the Friendica project
//
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace Friendica\Federation\Entity;

/**
 * @property-read int $targetServerId
 * @property-read int $failed         Maximum number of delivery failures among the delivery queue items targeting the server
 */
final class DeliveryQueueAggregate extends \Friendica\BaseEntity
{
	/** @var int */
	protected $targetServerId;
	/** @var int */
	protected $failed;

	public function __construct(int $targetServerId, int $failed)
	{
		$this->targetServerId = $targetServerId;
		$this->failed         = $failed;
	}
}
