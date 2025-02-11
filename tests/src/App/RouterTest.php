<?php

// Copyright (C) 2010-2024, the Friendica project
// SPDX-FileCopyrightText: 2010-2024 the Friendica project
//
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace Friendica\Test\src\App;

use Dice\Dice;
use Friendica\App\Arguments;
use Friendica\Core\Cache\Capability\ICanCache;
use Friendica\Core\Config\Capability\IManageConfigValues;
use Friendica\Core\L10n;
use Friendica\Core\Lock\Capability\ICanLock;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
	/** @var L10n|MockInterface */
	private $l10n;
	/**
	 * @var ICanCache
	 */
	private $cache;
	/**
	 * @var ICanLock
	 */
	private $lock;
	/**
	 * @var IManageConfigValues
	 */
	private $config;
	/**
	 * @var Dice
	 */
	private $dice;
	/**
	 * @var Arguments
	 */
	private $arguments;

	protected function setUp(): void
	{
		parent::setUp();

		self::markTestIncomplete('Router tests need refactoring!');

		/*
		$this->l10n = Mockery::mock(L10n::class);
		$this->l10n->shouldReceive('t')->andReturnUsing(function ($args) { return $args; });

		$this->cache = Mockery::mock(ICanCache::class);
		$this->cache->shouldReceive('get')->andReturn(null);
		$this->cache->shouldReceive('set')->andReturn(false);

		$this->lock = Mockery::mock(ICanLock::class);
		$this->lock->shouldReceive('acquire')->andReturn(true);
		$this->lock->shouldReceive('isLocked')->andReturn(false);

		$this->config = Mockery::mock(IManageConfigValues::class);

		$this->dice = new Dice();

		$this->arguments = Mockery::mock(Arguments::class);
		*/
	}

	public function test()
	{

	}
}
