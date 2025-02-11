<?php

// Copyright (C) 2010-2024, the Friendica project
// SPDX-FileCopyrightText: 2010-2024 the Friendica project
//
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace Friendica\Content\Conversation\Entity;

final class Network extends Timeline
{
	const STAR      = 'star';
	const MENTION   = 'mention';
	const RECEIVED  = 'received';
	const COMMENTED = 'commented';
	const CREATED   = 'created';
}
