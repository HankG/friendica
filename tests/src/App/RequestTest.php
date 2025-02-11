<?php

// Copyright (C) 2010-2024, the Friendica project
// SPDX-FileCopyrightText: 2010-2024 the Friendica project
//
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace Friendica\Test\src\App;

use Friendica\App\Request;
use Friendica\Core\Config\Capability\IManageConfigValues;
use Friendica\Test\MockedTestCase;

class RequestTest extends MockedTestCase
{
	public function dataServerArray(): array
	{
		return [
			'default' => [
				'server' => ['REMOTE_ADDR' => '1.2.3.4'],
				'config' => [
					'trusted_proxies'       => '',
					'forwarded_for_headers' => '',
				],
				'assertion' => '1.2.3.4',
			],
			'proxy_1' => [
				'server' => ['HTTP_X_FORWARDED_FOR' => '1.2.3.4, 4.5.6.7', 'REMOTE_ADDR' => '1.2.3.4'],
				'config' => [
					'trusted_proxies'       => '1.2.3.4',
					'forwarded_for_headers' => 'HTTP_X_FORWARDED_FOR',
				],
				'assertion' => '4.5.6.7',
			],
			'proxy_2' => [
				'server' => ['HTTP_X_FORWARDED_FOR' => '4.5.6.7, 1.2.3.4', 'REMOTE_ADDR' => '1.2.3.4'],
				'config' => [
					'trusted_proxies'       => '1.2.3.4',
					'forwarded_for_headers' => 'HTTP_X_FORWARDED_FOR',
				],
				'assertion' => '4.5.6.7',
			],
			'proxy_CIDR_multiple_proxies' => [
				'server' => ['HTTP_X_FORWARDED_FOR' => '4.5.6.7, 1.2.3.4', 'REMOTE_ADDR' => '10.0.1.1'],
				'config' => [
					'trusted_proxies'       => '10.0.0.0/16, 1.2.3.4',
					'forwarded_for_headers' => 'HTTP_X_FORWARDED_FOR',
				],
				'assertion' => '4.5.6.7',
			],
			'proxy_wrong_CIDR' => [
				'server' => ['HTTP_X_FORWARDED_FOR' => '4.5.6.7, 1.2.3.4', 'REMOTE_ADDR' => '10.1.0.1'],
				'config' => [
					'trusted_proxies'       => '10.0.0.0/24, 1.2.3.4',
					'forwarded_for_headers' => 'HTTP_X_FORWARDED_FOR',
				],
				'assertion' => '10.1.0.1',
			],
			'proxy_3' => [
				'server' => ['HTTP_X_FORWARDED_FOR' => '1.2.3.4, 4.5.6.7', 'REMOTE_ADDR' => '1.2.3.4'],
				'config' => [
					'trusted_proxies'       => '1.2.3.4',
					'forwarded_for_headers' => 'HTTP_X_FORWARDED_FOR',
				],
				'assertion' => '4.5.6.7',
			],
			'proxy_multiple_header_1' => [
				'server' => ['HTTP_X_FORWARDED' => '1.2.3.4, 4.5.6.7', 'REMOTE_ADDR' => '1.2.3.4'],
				'config' => [
					'trusted_proxies'       => '1.2.3.4',
					'forwarded_for_headers' => 'HTTP_X_FORWARDED_FOR, HTTP_X_FORWARDED',
				],
				'assertion' => '4.5.6.7',
			],
			'proxy_multiple_header_2' => [
				'server' => ['HTTP_X_FORWARDED_FOR' => '1.2.3.4', 'HTTP_X_FORWARDED' => '1.2.3.4, 4.5.6.7', 'REMOTE_ADDR' => '1.2.3.4'],
				'config' => [
					'trusted_proxies'       => '1.2.3.4',
					'forwarded_for_headers' => 'HTTP_X_FORWARDED_FOR, HTTP_X_FORWARDED',
				],
				'assertion' => '4.5.6.7',
			],
			'proxy_multiple_header_wrong' => [
				'server' => ['HTTP_X_FORWARDED_FOR' => '1.2.3.4', 'HTTP_X_FORWARDED' => '1.2.3.4, 4.5.6.7', 'REMOTE_ADDR' => '1.2.3.4'],
				'config' => [
					'trusted_proxies'       => '1.2.3.4',
					'forwarded_for_headers' => '',
				],
				'assertion' => '1.2.3.4',
			],
			'no_remote_addr' => [
				'server' => [],
				'config' => [
					'trusted_proxies'       => '1.2.3.4',
					'forwarded_for_headers' => '',
				],
				'assertion' => '0.0.0.0',
			],
		];
	}

	/**
	 * @dataProvider dataServerArray
	 */
	public function testRemoteAddress(array $server, array $config, string $assertion)
	{
		$configClass = \Mockery::mock(IManageConfigValues::class);
		$configClass->shouldReceive('get')->with('proxy', 'trusted_proxies', '')->andReturn($config['trusted_proxies']);
		$configClass->shouldReceive('get')->with('proxy', 'forwarded_for_headers', Request::DEFAULT_FORWARD_FOR_HEADER)->andReturn($config['forwarded_for_headers']);

		$request = new Request($configClass, $server);

		self::assertEquals($assertion, $request->getRemoteAddress());
	}
}
