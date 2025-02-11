<?php

// Copyright (C) 2010-2024, the Friendica project
// SPDX-FileCopyrightText: 2010-2024 the Friendica project
//
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace Friendica\Test\src\Module\Api\Mastodon\Timelines;

use Friendica\Test\ApiTestCase;

class HomeTest extends ApiTestCase
{
	/**
	 * Test the api_statuses_home_timeline() function.
	 *
	 * @return void
	 */
	public function testApiStatusesHomeTimeline()
	{
		self::markTestIncomplete('Needs Home to not set header during call (like at BaseApi::setLinkHeader');

		/*
		$_REQUEST['max_id']          = 10;
		$_REQUEST['exclude_replies'] = true;
		$_REQUEST['conversation_id'] = 1;
		$result                      = api_statuses_home_timeline('json');
		self::assertNotEmpty($result['status']);
		foreach ($result['status'] as $status) {
			self::assertStatus($status);
		}
		*/
	}

	/**
	 * Test the api_statuses_home_timeline() function with a negative page parameter.
	 *
	 * @return void
	 */
	public function testApiStatusesHomeTimelineWithNegativePage()
	{
		self::markTestIncomplete('Needs Home to not set header during call (like at BaseApi::setLinkHeader');

		/*
		$_REQUEST['page'] = -2;
		$result           = api_statuses_home_timeline('json');
		self::assertNotEmpty($result['status']);
		foreach ($result['status'] as $status) {
			self::assertStatus($status);
		}
		*/
	}

	/**
	 * Test the api_statuses_home_timeline() with an unallowed user.
	 *
	 * @return void
	 */
	public function testApiStatusesHomeTimelineWithUnallowedUser()
	{
		self::markTestIncomplete('Needs Home to not set header during call (like at BaseApi::setLinkHeader');

		/*
		$this->expectException(\Friendica\Network\HTTPException\UnauthorizedException::class);
		BasicAuth::setCurrentUserID();
		api_statuses_home_timeline('json');
		*/
	}

	/**
	 * Test the api_statuses_home_timeline() function with an RSS result.
	 *
	 * @return void
	 */
	public function testApiStatusesHomeTimelineWithRss()
	{
		self::markTestIncomplete('Needs Home to not set header during call (like at BaseApi::setLinkHeader');

		// $result = api_statuses_home_timeline('rss');
		// self::assertXml($result, 'statuses');
	}
}
