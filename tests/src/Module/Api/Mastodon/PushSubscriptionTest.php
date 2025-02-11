<?php

// Copyright (C) 2010-2024, the Friendica project
// SPDX-FileCopyrightText: 2010-2024 the Friendica project
//
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace Friendica\Test\src\Module\Api\Mastodon;

use Friendica\Test\ApiTestCase;

class PushSubscriptionTest extends ApiTestCase
{
	/**
	 * Test the api_account_verify_credentials() function.
	 *
	 * @return void
	 */
	public function testApiAccountVerifyCredentials(): void
	{
		$this->markTestIncomplete('Needs mocking of whole applications/Apps first');

		// $this->useHttpMethod(Router::POST);
		//
		// $response = (new PushSubscription(DI::appHelper(), DI::l10n(), DI::baseUrl(), DI::args(), DI::logger(), DI::profiler(), DI::apiResponse(), DI::mstdnSubscription(), DI::mstdnError(), []))
		// 	->run();
		//
		// $json = $this->toJson($response);
		// print_r($json);
		//
		// $this->assertEquals(1,1);
	}

	/**
	 * Test the api_account_verify_credentials() function without an authenticated user.
	 *
	 * @return void
	 */
	public function testApiAccountVerifyCredentialsWithoutAuthenticatedUser(): void
	{
		self::markTestIncomplete('Needs dynamic BasicAuth first');

		// $this->expectException(\Friendica\Network\HTTPException\UnauthorizedException::class);
		// BasicAuth::setCurrentUserID();
		// $_SESSION['authenticated'] = false;
		// api_account_verify_credentials('json');
	}
}
