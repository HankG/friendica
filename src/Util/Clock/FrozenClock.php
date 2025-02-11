<?php

// Copyright (C) 2010-2024, the Friendica project
// SPDX-FileCopyrightText: 2010-2024 the Friendica project
//
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace Friendica\Util\Clock;

use DateTimeImmutable;

/**
 * Inspired by lcobucci/clock
 * @see https://github.com/lcobucci/clock
 */
final class FrozenClock implements \Psr\Clock\ClockInterface
{
	/** @var DateTimeImmutable */
	private $now;

	public function __construct(DateTimeImmutable $now = null)
	{
		$this->now = $now ?? new DateTimeImmutable('now', new \DateTimeZone('UTC'));
	}

	/**
	 * @inheritDoc
	 */
	public function now(): DateTimeImmutable
	{
		return $this->now;
	}
}
