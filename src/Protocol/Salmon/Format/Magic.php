<?php

// Copyright (C) 2010-2024, the Friendica project
// SPDX-FileCopyrightText: 2010-2024 the Friendica project
//
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace Friendica\Protocol\Salmon\Format;

use Friendica\Util\Strings;
use phpseclib3\Math\BigInteger;

/**
 * This custom public RSA key format class is meant to be used with the \phpseclib3\Crypto\RSA::addFileFormat method.
 *
 * It handles Salmon's specific magic key string starting with "RSA." and which MIME type is application/magic-key or
 * application/magic-public-key
 *
 * @see https://web.archive.org/web/20160506073138/http://salmon-protocol.googlecode.com:80/svn/trunk/draft-panzer-magicsig-01.html#anchor13
 */
class Magic
{
	public static function load($key, $password = ''): array
	{
		if (!is_string($key)) {
			throw new \UnexpectedValueException('Key should be a string - not a ' . gettype($key));
		}

		$key_info = explode('.', $key);

		if (count($key_info) !== 3) {
			throw new \UnexpectedValueException('Key should have three components separated by periods');
		}

		if ($key_info[0] !== 'RSA') {
			throw new \UnexpectedValueException('Key first component should be "RSA"');
		}

		if (preg_match('#[+/]#', $key_info[1])
			|| preg_match('#[+/]#', $key_info[1])
		) {
			throw new \UnexpectedValueException('Wrong encoding, expecting Base64URLencoding');
		}

		$m = Strings::base64UrlDecode($key_info[1]);
		$e = Strings::base64UrlDecode($key_info[2]);

		if (!$m || !$e) {
			throw new \UnexpectedValueException('Base64 decoding produced an error');
		}

		return [
			'modulus'        => new BigInteger($m, 256),
			'publicExponent' => new BigInteger($e, 256),
			'isPublicKey'    => true,
		];
	}

	public static function savePublicKey(BigInteger $n, BigInteger $e, array $options = []): string
	{
		return 'RSA.' . Strings::base64UrlEncode($n->toBytes(), true) . '.' . Strings::base64UrlEncode($e->toBytes(), true);
	}
}
