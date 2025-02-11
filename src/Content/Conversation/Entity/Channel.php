<?php

// Copyright (C) 2010-2024, the Friendica project
// SPDX-FileCopyrightText: 2010-2024 the Friendica project
//
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace Friendica\Content\Conversation\Entity;

class Channel extends Timeline
{
	const WHATSHOT         = 'whatshot';
	const FORYOU           = 'foryou';
	const DISCOVER         = 'discover';
	const FOLLOWERS        = 'followers';
	const SHARERSOFSHARERS = 'sharersofsharers';
	const QUIETSHARERS     = 'quietsharers';
	const IMAGE            = 'image';
	const VIDEO            = 'video';
	const AUDIO            = 'audio';
	const LANGUAGE         = 'language';
}
