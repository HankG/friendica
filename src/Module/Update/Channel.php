<?php

// Copyright (C) 2010-2024, the Friendica project
// SPDX-FileCopyrightText: 2010-2024 the Friendica project
//
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace Friendica\Module\Update;

use Friendica\Content\Conversation;
use Friendica\Core\System;
use Friendica\Module\Conversation\Channel as ChannelModule;

/**
 * Asynchronous update module for the Channel page
 *
 * @package Friendica\Module\Update
 */
class Channel extends ChannelModule
{
	protected function rawContent(array $request = [])
	{
		$this->parseRequest($request);

		$o = '';
		if ($this->update || $this->force) {
			if ($this->channel->isTimeline($this->selectedTab) || $this->userDefinedChannel->isTimeline($this->selectedTab, $this->session->getLocalUserId())) {
				$items = $this->getChannelItems($request, $this->session->getLocalUserId());
			} else {
				$items = $this->getCommunityItems();
			}

			$o = $this->conversation->render($items, Conversation::MODE_CHANNEL, true, false, 'created', $this->session->getLocalUserId());
		}

		System::htmlUpdateExit($o);
	}
}
