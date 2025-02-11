<?php

// Copyright (C) 2010-2024, the Friendica project
// SPDX-FileCopyrightText: 2010-2024 the Friendica project
//
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace Friendica\Protocol;

use Friendica\Protocol\Salmon\Format\Magic;
use phpseclib3\Crypt\PublicKeyLoader;

/**
 * Salmon Protocol class
 *
 * The Salmon Protocol is a message exchange protocol running over HTTP designed to decentralize commentary
 * and annotations made against newsfeed articles such as blog posts.
 */
class Salmon
{
	/**
	 * @param string $pubkey public key
	 * @return string
	 * @throws \Exception
	 */
	public static function salmonKey(string $pubkey): string
	{
		\phpseclib3\Crypt\RSA::addFileFormat(Magic::class);

		return PublicKeyLoader::load($pubkey)->toString('Magic');
	}

	/**
	 * @param string $magic Magic key format starting with "RSA."
	 * @return string
	 */
	public static function magicKeyToPem(string $magic): string
	{
		\phpseclib3\Crypt\RSA::addFileFormat(Magic::class);

		return (string) PublicKeyLoader::load($magic);
	}
}
