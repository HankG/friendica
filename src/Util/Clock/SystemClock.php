<?php

// Copyright (C) 2010-2024, the Friendica project
// SPDX-FileCopyrightText: 2010-2024 the Friendica project
//
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace Friendica\Util\Clock;

use DateTimeImmutable;
use DateTimeZone;

/**
 * Inspired by lcobucci/clock
 * @see https://github.com/lcobucci/clock
 */
final class SystemClock implements \Psr\Clock\ClockInterface
{
	/** @var DateTimeZone */
	private $timezone;

	public function __construct(DateTimeZone $timezone = null)
	{
		$this->timezone = $timezone ?? new DateTimeZone('UTC');
	}

	/**
	 * @inheritDoc
	 */
	public function now(): DateTimeImmutable
	{
		return new DateTimeImmutable('now', $this->timezone);
	}
}
