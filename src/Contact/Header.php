<?php

// Copyright (C) 2010-2024, the Friendica project
// SPDX-FileCopyrightText: 2010-2024 the Friendica project
//
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace Friendica\Contact;

use Friendica\Core\Config\Capability\IManageConfigValues;

class Header
{
	/** @var IManageConfigValues */
	private $config;

	public function __construct(IManageConfigValues $config)
	{
		$this->config = $config;
	}

	/**
	 * Returns the Mastodon banner path relative to the Friendica folder.
	 *
	 * Ensures the existence of a leading slash.
	 *
	 * @return string
	 */
	public function getMastodonBannerPath(): string
	{
		return '/' . ltrim($this->config->get('api', 'mastodon_banner'), '/');
	}
}
