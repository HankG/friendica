<?php

// Copyright (C) 2010-2024, the Friendica project
// SPDX-FileCopyrightText: 2010-2024 the Friendica project
//
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace Friendica\Core\Addon\Model;

use Friendica\Core\Addon\Capability\ICanLoadAddons;
use Friendica\Core\Addon\Exception\AddonInvalidConfigFileException;
use Friendica\Core\Config\Capability\IManageConfigValues;
use Friendica\Util\Strings;

class AddonLoader implements ICanLoadAddons
{
	const STATIC_PATH = 'static';
	/** @var string */
	protected $basePath;
	/** @var IManageConfigValues */
	protected $config;

	public function __construct(string $basePath, IManageConfigValues $config)
	{
		$this->basePath = $basePath;
		$this->config   = $config;
	}

	/** {@inheritDoc} */
	public function getActiveAddonConfig(string $configName): array
	{
		$addons       = array_keys(array_filter($this->config->get('addons') ?? []));
		$returnConfig = [];

		foreach ($addons as $addon) {
			$addonName = Strings::sanitizeFilePathItem(trim($addon));

			$configFile = $this->basePath . '/addon/' . $addonName . '/' . static::STATIC_PATH . '/' . $configName . '.config.php';

			if (!file_exists($configFile)) {
				// Addon unmodified, skipping
				continue;
			}

			$config = include $configFile;

			if (!is_array($config)) {
				throw new AddonInvalidConfigFileException('Error loading config file ' . $configFile);
			}

			$returnConfig = array_merge_recursive($returnConfig, $config);
		}

		return $returnConfig;
	}
}
