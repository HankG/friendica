<?php

// Copyright (C) 2010-2024, the Friendica project
// SPDX-FileCopyrightText: 2010-2024 the Friendica project
//
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace Friendica\Test\src\App;

use Friendica\App\BaseURL;
use Friendica\Core\Config\Model\ReadOnlyFileConfig;
use Friendica\Core\Config\ValueObject\Cache;
use Friendica\Network\HTTPException\InternalServerErrorException;
use Friendica\Test\MockedTestCase;
use Psr\Log\NullLogger;

class BaseURLTest extends MockedTestCase
{
	public function dataSystemUrl(): array
	{
		return [
			'default' => [
				'input'     => ['system' => ['url' => 'https://friendica.local',],],
				'server'    => [],
				'assertion' => 'https://friendica.local',
			],
			'subPath' => [
				'input'     => ['system' => ['url' => 'https://friendica.local/subpath',],],
				'server'    => [],
				'assertion' => 'https://friendica.local/subpath',
			],
			'empty' => [
				'input'     => [],
				'server'    => [],
				'assertion' => 'http://localhost',
			],
			'serverArrayStandard' => [
				'input'  => [],
				'server' => [
					'HTTPS'        => 'on',
					'HTTP_HOST'    => 'friendica.server',
					'REQUEST_URI'  => '/test/it?with=query',
					'QUERY_STRING' => 'pagename=test/it',
				],
				'assertion' => 'https://friendica.server',
			],
			'serverArraySubPath' => [
				'input'  => [],
				'server' => [
					'HTTPS'        => 'on',
					'HTTP_HOST'    => 'friendica.server',
					'REQUEST_URI'  => '/test/it/now?with=query',
					'QUERY_STRING' => 'pagename=it/now',
				],
				'assertion' => 'https://friendica.server/test',
			],
			'serverArraySubPath2' => [
				'input'  => [],
				'server' => [
					'HTTPS'        => 'on',
					'HTTP_HOST'    => 'friendica.server',
					'REQUEST_URI'  => '/test/it/now?with=query',
					'QUERY_STRING' => 'pagename=now',
				],
				'assertion' => 'https://friendica.server/test/it',
			],
			'serverArraySubPath3' => [
				'input'  => [],
				'server' => [
					'HTTPS'        => 'on',
					'HTTP_HOST'    => 'friendica.server',
					'REQUEST_URI'  => '/test/it/now?with=query',
					'QUERY_STRING' => 'pagename=test/it/now',
				],
				'assertion' => 'https://friendica.server',
			],
			'serverArrayWithoutQueryString1' => [
				'input'  => [],
				'server' => [
					'HTTPS'       => 'on',
					'HTTP_HOST'   => 'friendica.server',
					'REQUEST_URI' => '/test/it/now?with=query',
				],
				'assertion' => 'https://friendica.server/test/it/now',
			],
			'serverArrayWithoutQueryString2' => [
				'input'  => [],
				'server' => [
					'HTTPS'       => 'on',
					'HTTP_HOST'   => 'friendica.server',
					'REQUEST_URI' => '',
				],
				'assertion' => 'https://friendica.server',
			],
			'serverArrayWithoutQueryString3' => [
				'input'  => [],
				'server' => [
					'HTTPS'       => 'on',
					'HTTP_HOST'   => 'friendica.server',
					'REQUEST_URI' => '/',
				],
				'assertion' => 'https://friendica.server',
			],
		];
	}

	/**
	 * @dataProvider dataSystemUrl
	 */
	public function testDetermine(array $input, array $server, string $assertion)
	{
		$origServerGlobal = $_SERVER;

		$_SERVER = array_merge_recursive($_SERVER, $server);
		$config  = new ReadOnlyFileConfig(new Cache($input));

		$baseUrl = new BaseURL($config, new NullLogger(), $server);

		self::assertEquals($assertion, (string)$baseUrl);

		$_SERVER = $origServerGlobal;
	}

	public function dataRemove(): array
	{
		return [
			'same' => [
				'base'      => ['system' => ['url' => 'https://friendica.local',],],
				'origUrl'   => 'https://friendica.local/test/picture.png',
				'assertion' => 'test/picture.png',
			],
			'other' => [
				'base'      => ['system' => ['url' => 'https://friendica.local',],],
				'origUrl'   => 'https://friendica.other/test/picture.png',
				'assertion' => 'https://friendica.other/test/picture.png',
			],
			'samSubPath' => [
				'base'      => ['system' => ['url' => 'https://friendica.local/test',],],
				'origUrl'   => 'https://friendica.local/test/picture.png',
				'assertion' => 'picture.png',
			],
			'otherSubPath' => [
				'base'      => ['system' => ['url' => 'https://friendica.local/test',],],
				'origUrl'   => 'https://friendica.other/test/picture.png',
				'assertion' => 'https://friendica.other/test/picture.png',
			],
		];
	}

	/**
	 * @dataProvider dataRemove
	 */
	public function testRemove(array $base, string $origUrl, string $assertion)
	{
		$config  = new ReadOnlyFileConfig(new Cache($base));
		$baseUrl = new BaseURL($config, new NullLogger());

		self::assertEquals($assertion, $baseUrl->remove($origUrl));
	}

	/**
	 * Test that redirect to external domains fails
	 */
	public function testRedirectException()
	{
		self::expectException(InternalServerErrorException::class);
		self::expectExceptionMessage('https://friendica.other is not a relative path, please use System::externalRedirect');

		$config = new ReadOnlyFileConfig(new Cache([
			'system' => [
				'url' => 'https://friendica.local',
			]
		]));
		$baseUrl = new BaseURL($config, new NullLogger());
		$baseUrl->redirect('https://friendica.other');
	}
}
