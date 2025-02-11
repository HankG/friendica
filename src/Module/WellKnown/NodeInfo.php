<?php

// Copyright (C) 2010-2024, the Friendica project
// SPDX-FileCopyrightText: 2010-2024 the Friendica project
//
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace Friendica\Module\WellKnown;

use Friendica\BaseModule;
use Friendica\DI;

/**
 * Standardized way of exposing metadata about a server running one of the distributed social networks.
 * @see https://github.com/jhass/nodeinfo/blob/master/PROTOCOL.md
 */
class NodeInfo extends BaseModule
{
	protected function rawContent(array $request = [])
	{
		$nodeinfo = [
			'links' => [
				[
					'rel'  => 'http://nodeinfo.diaspora.software/ns/schema/1.0',
					'href' => DI::baseUrl() . '/nodeinfo/1.0'
				],
				[
					'rel'  => 'http://nodeinfo.diaspora.software/ns/schema/2.0',
					'href' => DI::baseUrl() . '/nodeinfo/2.0'
				],
				[
					'rel'  => 'http://nodeinfo.diaspora.software/ns/schema/2.1',
					'href' => DI::baseUrl() . '/nodeinfo/2.1'
				],
				[
					'rel'  => 'http://nodeinfo.diaspora.software/ns/schema/2.2',
					'href' => DI::baseUrl() . '/nodeinfo/2.2'
				],
			]
		];

		$this->jsonExit($nodeinfo);
	}
}
