<?php

// Copyright (C) 2010-2024, the Friendica project
// SPDX-FileCopyrightText: 2010-2024 the Friendica project
//
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace Friendica\Profile\ProfileField\Collection;

use Friendica\BaseCollection;
use Friendica\Profile\ProfileField\Entity;

class ProfileFields extends BaseCollection
{
	public function current(): Entity\ProfileField
	{
		return parent::current();
	}

	/**
	 * @param callable $callback
	 * @return ProfileFields (as an extended form of BaseCollection)
	 */
	public function map(callable $callback): BaseCollection
	{
		return parent::map($callback);
	}

	/**
	 * @param callable|null $callback
	 * @param int           $flag
	 * @return ProfileFields as an extended version of BaseCollection
	 */
	public function filter(callable $callback = null, int $flag = 0): BaseCollection
	{
		return parent::filter($callback, $flag);
	}
}
