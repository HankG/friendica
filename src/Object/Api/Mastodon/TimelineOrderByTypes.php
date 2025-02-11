<?php

// Copyright (C) 2010-2024, the Friendica project
// SPDX-FileCopyrightText: 2010-2024 the Friendica project
//
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace Friendica\Object\Api\Mastodon;

/**
 * Enumeration of order types that can be requested
 */
abstract class TimelineOrderByTypes
{
	const CHANGED   = 'changed';
	const CREATED   = 'created';
	const COMMENTED = 'commented';
	const EDITED    = 'edited';
	const ID        = 'id';
	const RECEIVED  = 'received';
}
