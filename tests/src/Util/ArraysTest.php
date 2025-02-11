<?php

// Copyright (C) 2010-2024, the Friendica project
// SPDX-FileCopyrightText: 2010-2024 the Friendica project
//
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace Friendica\Test\src\Util;

use Friendica\Util\Arrays;
use PHPUnit\Framework\TestCase;

/**
 * Array utility testing class
 */
class ArraysTest extends TestCase
{
	/**
	 * Tests if an empty array and an empty delimiter returns an empty string.
	 */
	public function testEmptyArrayEmptyDelimiter()
	{
		$str = Arrays::recursiveImplode([], '');
		self::assertEmpty($str);
	}

	/**
	 * Tests if an empty array and a non-empty delimiter returns an empty string.
	 */
	public function testEmptyArrayNonEmptyDelimiter()
	{
		$str = Arrays::recursiveImplode([], ',');
		self::assertEmpty($str);
	}

	/**
	 * Tests if a non-empty array and an empty delimiter returns the value (1).
	 */
	public function testNonEmptyArrayEmptyDelimiter()
	{
		$str = Arrays::recursiveImplode([1], '');
		self::assertSame($str, '1');
	}

	/**
	 * Tests if a non-empty array and an empty delimiter returns the value (12).
	 */
	public function testNonEmptyArray2EmptyDelimiter()
	{
		$str = Arrays::recursiveImplode([1, 2], '');
		self::assertSame($str, '12');
	}

	/**
	 * Tests if a non-empty array and a non-empty delimiter returns the value (1).
	 */
	public function testNonEmptyArrayNonEmptyDelimiter()
	{
		$str = Arrays::recursiveImplode([1], ',');
		self::assertSame($str, '1');
	}

	/**
	 * Tests if a non-empty array and a non-empty delimiter returns the value (1,2).
	 */
	public function testNonEmptyArray2NonEmptyDelimiter()
	{
		$str = Arrays::recursiveImplode([1, 2], ',');
		self::assertSame($str, '1,2');
	}

	/**
	 * Tests if a 2-dim array and an empty delimiter returns the expected string.
	 */
	public function testEmptyMultiArray2EmptyDelimiter()
	{
		$str = Arrays::recursiveImplode([[1], []], '');
		self::assertSame($str, '{1}{}');
	}

	/**
	 * Tests if a 2-dim array and an empty delimiter returns the expected string.
	 */
	public function testEmptyMulti2Array2EmptyDelimiter()
	{
		$str = Arrays::recursiveImplode([[1], [2]], '');
		self::assertSame($str, '{1}{2}');
	}

	/**
	 * Tests if a 2-dim array and a non-empty delimiter returns the expected string.
	 */
	public function testEmptyMultiArray2NonEmptyDelimiter()
	{
		$str = Arrays::recursiveImplode([[1], []], ',');
		self::assertSame($str, '{1},{}');
	}

	/**
	 * Tests if a 2-dim array and a non-empty delimiter returns the expected string.
	 */
	public function testEmptyMulti2Array2NonEmptyDelimiter()
	{
		$str = Arrays::recursiveImplode([[1], [2]], ',');
		self::assertSame($str, '{1},{2}');
	}

	/**
	 * Tests if a 3-dim array and a non-empty delimiter returns the expected string.
	 */
	public function testEmptyMulti3Array2NonEmptyDelimiter()
	{
		$str = Arrays::recursiveImplode([[1], [2, [3]]], ',');
		self::assertSame($str, '{1},{2,{3}}');
	}

	/**
	 * Test the Arrays::walkRecursive() function.
	 */
	public function testApiWalkRecursive()
	{
		$array = ['item1'];
		self::assertEquals(
			$array,
			Arrays::walkRecursive(
				$array,
				function () {
					// Should we test this with a callback that actually does something?
					return true;
				}
			)
		);
	}

	/**
	 * Test the Arrays::walkRecursive() function with an array.
	 *
	 * @return void
	 */
	public function testApiWalkRecursiveWithArray()
	{
		$array = [['item1'], ['item2']];
		self::assertEquals(
			$array,
			Arrays::walkRecursive(
				$array,
				function () {
					// Should we test this with a callback that actually does something?
					return true;
				}
			)
		);
	}
}
