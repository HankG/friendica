<?php

// Copyright (C) 2010-2024, the Friendica project
// SPDX-FileCopyrightText: 2010-2024 the Friendica project
//
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace Friendica\Core\Hooks\Capability;

/**
 * An enum of hook types, based on behavioral design patterns
 * @see https://refactoring.guru/design-patterns/behavioral-patterns
 */
interface BehavioralHookType
{
	/**
	 * Defines the key for the list of strategy-hooks.
	 *
	 * @see https://refactoring.guru/design-patterns/strategy
	 */
	const STRATEGY = 'strategy';
	const EVENT    = 'event';
}
