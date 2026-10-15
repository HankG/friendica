<?php

// Copyright (C) 2010-2026, the Friendica project
// SPDX-FileCopyrightText: 2010-2026 the Friendica project
//
// SPDX-License-Identifier: AGPL-3.0-or-later

declare(strict_types=1);

namespace Friendica\Test\Integration\Module\Api\Mastodon;

use Friendica\Core\EarlyExitException;
use Friendica\DI;
use Friendica\Module\Api\Mastodon\Conversations;
use Friendica\Test\ApiTestCase;
use Friendica\Test\Util\AuthTestConfig;
use GuzzleHttp\Psr7\ServerRequest;

final class ConversationsTest extends ApiTestCase
{
	public function testApiConversationShow(): void
	{
		$module = $this->createModule();

		$request = new ServerRequest('GET', 'https://friendica.local/api/v1/conversations');

		try {
			$module->handleRequest($request);
			self::fail('Expected EarlyExitException');
		} catch (EarlyExitException $e) { // @phpstan-ignore catch.neverThrown
			$conversations = $this->toJson($e->getResponse());

			self::assertSame([], $conversations);
		}
	}

	public function testApiConversationShowWithId(): void
	{
		DI::dba()->insert('conv', [
			'id'      => 1,
			'guid'    => 'conversation-1',
			'recips'  => 'sender@example.org;recipient@example.org',
			'uid'     => 42,
			'creator' => 'sender@example.org',
			'created' => '2020-01-01 12:00:00',
			'updated' => '2020-01-01 12:00:00',
			'subject' => 'First conversation',
		]);
		DI::dba()->insert('conv', [
			'id'      => 2,
			'guid'    => 'conversation-2',
			'recips'  => 'sender@example.org;recipient@example.org',
			'uid'     => 42,
			'creator' => 'sender@example.org',
			'created' => '2020-01-01 12:00:00',
			'updated' => '2020-01-01 12:00:00',
			'subject' => 'Second conversation',
		]);

		$module = $this->createModule();

		$request = new ServerRequest('GET', 'https://friendica.local/api/v1/conversations');

		try {
			$module->handleRequest($request);
			self::fail('Expected EarlyExitException');
		} catch (EarlyExitException $e) { // @phpstan-ignore catch.neverThrown
			$conversations = $this->toJson($e->getResponse());

			self::assertCount(2, $conversations);
			self::assertEquals(['2', '1'], array_column($conversations, 'id'));
			self::assertSame([], $conversations[0]->accounts);
			self::assertFalse($conversations[0]->unread);
			self::assertNull($conversations[0]->last_status);
		}
	}

	public function testApiConversationShowWithMaxId(): void
	{
		DI::dba()->insert('conv', [
			'id'      => 1,
			'guid'    => 'conversation-1',
			'recips'  => 'sender@example.org;recipient@example.org',
			'uid'     => 42,
			'creator' => 'sender@example.org',
			'created' => '2020-01-01 12:00:00',
			'updated' => '2020-01-01 12:00:00',
			'subject' => 'First conversation',
		]);
		DI::dba()->insert('conv', [
			'id'      => 2,
			'guid'    => 'conversation-2',
			'recips'  => 'sender@example.org;recipient@example.org',
			'uid'     => 42,
			'creator' => 'sender@example.org',
			'created' => '2020-01-01 12:00:00',
			'updated' => '2020-01-01 12:00:00',
			'subject' => 'Second conversation',
		]);

		$module = $this->createModule();

		$request = (new ServerRequest('GET', 'https://friendica.local/api/v1/conversations'))
			->withQueryParams(['max_id' => '2']);

		try {
			$module->handleRequest($request);
			self::fail('Expected EarlyExitException');
		} catch (EarlyExitException $e) { // @phpstan-ignore catch.neverThrown
			$conversations = $this->toJson($e->getResponse());

			self::assertCount(1, $conversations);
			self::assertEquals(['1'], array_column($conversations, 'id'));
		}
	}

	public function testApiConversationShowWithMinId(): void
	{
		DI::dba()->insert('conv', [
			'id'      => 1,
			'guid'    => 'conversation-1',
			'recips'  => 'sender@example.org;recipient@example.org',
			'uid'     => 42,
			'creator' => 'sender@example.org',
			'created' => '2020-01-01 12:00:00',
			'updated' => '2020-01-01 12:00:00',
			'subject' => 'First conversation',
		]);
		DI::dba()->insert('conv', [
			'id'      => 2,
			'guid'    => 'conversation-2',
			'recips'  => 'sender@example.org;recipient@example.org',
			'uid'     => 42,
			'creator' => 'sender@example.org',
			'created' => '2020-01-01 12:00:00',
			'updated' => '2020-01-01 12:00:00',
			'subject' => 'Second conversation',
		]);

		$module = $this->createModule();

		$request = (new ServerRequest('GET', 'https://friendica.local/api/v1/conversations'))
			->withQueryParams(['min_id' => '1']);

		try {
			$module->handleRequest($request);
			self::fail('Expected EarlyExitException');
		} catch (EarlyExitException $e) { // @phpstan-ignore catch.neverThrown
			$conversations = $this->toJson($e->getResponse());

			self::assertCount(1, $conversations);
			self::assertEquals(['2'], array_column($conversations, 'id'));
		}
	}

	public function testApiConversationShowWithUnallowedUser(): void
	{
		AuthTestConfig::$authenticated = false;

		$module = $this->createModule();

		$request = new ServerRequest('GET', 'https://friendica.local/api/v1/conversations');

		try {
			$module->handleRequest($request);
			self::fail('Expected EarlyExitException');
		} catch (EarlyExitException $e) { // @phpstan-ignore catch.neverThrown
			self::assertEquals(401, $e->getResponse()->getStatusCode());
		}
	}

	private function createModule(): Conversations
	{
		return new Conversations(DI::mstdnError(), DI::appHelper(), DI::l10n(), DI::baseUrl(), DI::args(), DI::logger(), DI::profiler(), DI::apiResponse(), []);
	}
}
