<?php

// Copyright (C) 2010-2024, the Friendica project
// SPDX-FileCopyrightText: 2010-2024 the Friendica project
//
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace Friendica\Core\Addon\Capability;

/**
 * Interface for loading Addons specific content
 */
interface ICanLoadAddons
{
	/**
	 * Returns a merged config array of all active addons for a given config-name
	 *
	 * @param string $configName The config-name (config-file at the static directory, like 'hooks' => '{addon}/static/hooks.config.php)
	 *
	 * @return array the merged array
	 */
	public function getActiveAddonConfig(string $configName): array;
}
