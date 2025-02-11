<?php

// Copyright (C) 2010-2024, the Friendica project
// SPDX-FileCopyrightText: 2010-2024 the Friendica project
//
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace Friendica\Model\Notification;

/**
 * Enum for different otypes of the Notify
 */
class ObjectType
{
	const PERSON = 'person';
	const MAIL   = 'mail';
	const ITEM   = 'item';
	const INTRO  = 'intro';
}
