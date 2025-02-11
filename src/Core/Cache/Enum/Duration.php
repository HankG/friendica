<?php

// Copyright (C) 2010-2024, the Friendica project
// SPDX-FileCopyrightText: 2010-2024 the Friendica project
//
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace Friendica\Core\Cache\Enum;

/**
 * Enumeration for cache durations
 */
abstract class Duration
{
	const MONTH        = 2592000;
	const HOUR         = 3600;
	const HALF_HOUR    = 1800;
	const QUARTER_HOUR = 900;
	const MINUTE       = 60;
	const WEEK         = 604800;
	const INFINITE     = 0;
	const DAY          = 86400;
	const FIVE_MINUTES = 300;
}
