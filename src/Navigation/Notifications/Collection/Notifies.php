<?php

// Copyright (C) 2010-2024, the Friendica project
// SPDX-FileCopyrightText: 2010-2024 the Friendica project
//
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace Friendica\Navigation\Notifications\Collection;

use Friendica\BaseCollection;
use Friendica\Navigation\Notifications\Entity;

class Notifies extends BaseCollection
{
	/**
	 * @return Entity\Notify
	 */
	public function current(): Entity\Notify
	{
		return parent::current();
	}

	public function setSeen(): Notifies
	{
		return $this->map(function (Entity\Notify $Notify) {
			$Notify->setSeen();
		});
	}
}
