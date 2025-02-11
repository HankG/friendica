<?php

// Copyright (C) 2010-2024, the Friendica project
// SPDX-FileCopyrightText: 2010-2024 the Friendica project
//
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace Friendica\Test\src\Protocol;

use Friendica\Protocol\Salmon;

class SalmonTest extends \PHPUnit\Framework\TestCase
{
	public function dataMagic(): array
	{
		return [
			'salmon' => [
				'magic' => file_get_contents(__DIR__ . '/../../datasets/crypto/rsa/salmon-public-magic'),
				'pem'   => file_get_contents(__DIR__ . '/../../datasets/crypto/rsa/salmon-public-pem'),
			],
		];
	}

	/**
	 * @dataProvider dataMagic
	 *
	 * @param $magic
	 * @param $pem
	 * @return void
	 * @throws \Exception
	 */
	public function testSalmonKey($magic, $pem)
	{
		$this->assertEquals($magic, Salmon::salmonKey($pem));
	}

	/**
	 * @dataProvider dataMagic
	 *
	 * @param $magic
	 * @param $pem
	 * @return void
	 */
	public function testMagicKeyToPem($magic, $pem)
	{
		$this->assertEquals($pem, Salmon::magicKeyToPem($magic));
	}

	public function dataMagicFailure(): array
	{
		return [
			'empty string' => [
				'magic' => '',
			],
			'Missing algo' => [
				'magic' => 'tvsoBZbLUvqWs-0d8C5hVQLjLCjjxyZb17Rm8_9FDqBYUigBSFDcJCzG27FM-zuddwpgJB0vDuPKQnt59kKRsw.AQAB',
			],
			'Missing modulus' => [
				'magic' => 'RSA.AQAB',
			],
			'Missing exponent' => [
				'magic' => 'RSA.tvsoBZbLUvqWs-0d8C5hVQLjLCjjxyZb17Rm8_9FDqBYUigBSFDcJCzG27FM-zuddwpgJB0vDuPKQnt59kKRsw',
			],
			'Missing key parts' => [
				'magic' => 'RSA.',
			],
			'Too many parts' => [
				'magic' => 'RSA.tvsoBZbLUvqWs-0d8C5hVQLjLCjjxyZb17Rm8_9FDqBYUigBSFDcJCzG27FM-zuddwpgJB0vDuPKQnt59kKRsw.AQAB.AQAB',
			],
			'Wrong encoding' => [
				'magic' => 'RSA.tvsoBZbLUvqWs-0d8C5hVQLjLCjjxyZb17Rm8/9FDqBYUigBSFDcJCzG27FM+zuddwpgJB0vDuPKQnt59kKRsw.AQAB',
			],
			'Wrong algo' => [
				'magic' => 'ECDSA.tvsoBZbLUvqWs-0d8C5hVQLjLCjjxyZb17Rm8_9FDqBYUigBSFDcJCzG27FM-zuddwpgJB0vDuPKQnt59kKRsw.AQAB',
			],
		];
	}

	/**
	 * @dataProvider dataMagicFailure
	 *
	 * @param $magic
	 * @return void
	 */
	public function testMagicKeyToPemFailure($magic)
	{
		$this->expectException(\Throwable::class);

		Salmon::magicKeyToPem($magic);
	}
}
