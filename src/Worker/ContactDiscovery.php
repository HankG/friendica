<?php

// Copyright (C) 2010-2024, the Friendica project
// SPDX-FileCopyrightText: 2010-2024 the Friendica project
//
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace Friendica\Worker;

use Friendica\Model\Contact;

class ContactDiscovery
{
	/**
	 * Discover contact relations
	 * @param string $url
	 */
	public static function execute(string $url)
	{
		Contact\Relation::discoverByUrl($url);
	}
}
