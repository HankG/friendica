<?php

// Copyright (C) 2010-2024, the Friendica project
// SPDX-FileCopyrightText: 2010-2024 the Friendica project
//
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace Friendica\Moderation\Entity\Report;

/**
 * @property-read int    $lineId Terms of service text line number
 * @property-read string $text   Terms of service rule text
 */
final class Rule extends \Friendica\BaseEntity
{
	/** @var int */
	protected $lineId;
	/** @var string */
	protected $text;

	public function __construct(int $lineId, string $text)
	{
		$this->lineId = $lineId;
		$this->text   = $text;
	}
}
