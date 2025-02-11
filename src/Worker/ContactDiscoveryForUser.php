<?php

// Copyright (C) 2010-2024, the Friendica project
// SPDX-FileCopyrightText: 2010-2024 the Friendica project
//
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace Friendica\Worker;

use Friendica\Model\Contact;

class ContactDiscoveryForUser
{
	/**
	 * Discover contact relations
	 */
	public static function execute(int $uid)
	{
		Contact\Relation::discoverByUser($uid);
	}
}
