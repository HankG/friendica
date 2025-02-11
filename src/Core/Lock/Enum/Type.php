<?php

// Copyright (C) 2010-2024, the Friendica project
// SPDX-FileCopyrightText: 2010-2024 the Friendica project
//
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace Friendica\Core\Lock\Enum;

use Friendica\Core\Cache\Type\DatabaseCache;

/**
 * Enumeration for lock types
 *
 * There's no "Cache" lock type, because the type depends on the concrete, used cache
 */
abstract class Type
{
	const DATABASE  = DatabaseCache::NAME;
	const SEMAPHORE = 'semaphore';
}
