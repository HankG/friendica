<?php

// Copyright (C) 2010-2024, the Friendica project
// SPDX-FileCopyrightText: 2010-2024 the Friendica project
//
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace Friendica\Navigation\Notifications\Collection;

use Friendica\BaseCollection;
use Friendica\Navigation\Notifications\Entity;

class Notifications extends BaseCollection
{
	/**
	 * @return Entity\Notification
	 */
	public function current(): Entity\Notification
	{
		return parent::current();
	}

	public function setSeen(): Notifications
	{
		return $this->map(function (Entity\Notification $Notification) {
			$Notification->setSeen();
		});
	}

	public function setDismissed(): Notifications
	{
		return $this->map(function (Entity\Notification $Notification) {
			$Notification->setDismissed();
		});
	}

	public function countUnseen(): int
	{
		return array_reduce($this->getArrayCopy(), function (int $carry, Entity\Notification $Notification) {
			return $carry + ($Notification->seen ? 0 : 1);
		}, 0);
	}
}
