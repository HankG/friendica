<?php
/**
 * @copyright Copyright (C) 2010-2024, the Friendica project
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 */

namespace Friendica\Module\Ping;

use Friendica\App;
use Friendica\App\Mode;
use Friendica\Content\Conversation;
use Friendica\Content\Conversation\Factory\Timeline as TimelineFactory;
use Friendica\Content\Conversation\Repository\UserDefinedChannel;
use Friendica\Content\Conversation\Factory\Channel as ChannelFactory;
use Friendica\Content\Conversation\Factory\UserDefinedChannel as UserDefinedChannelFactory;
use Friendica\Content\Conversation\Factory\Community as CommunityFactory;
use Friendica\Content\Conversation\Factory\Network as NetworkFactory;
use Friendica\Core\Cache\Capability\ICanCache;
use Friendica\Core\Config\Capability\IManageConfigValues;
use Friendica\Core\L10n;
use Friendica\Core\Lock\Capability\ICanLock;
use Friendica\Core\PConfig\Capability\IManagePersonalConfigValues;
use Friendica\Core\Session\Capability\IHandleUserSessions;
use Friendica\Core\System;
use Friendica\Database\Database;
use Friendica\Module\Conversation\Network as NetworkModule;
use Friendica\Module\Response;
use Friendica\Navigation\SystemMessages;
use Friendica\Util\Profiler;
use Psr\Log\LoggerInterface;

class Network extends NetworkModule
{
	/**
	 * @var ICanLock
	 */
	private $lock;

	public function __construct(ICanLock $lock, UserDefinedChannelFactory $userDefinedChannel, NetworkFactory $network, CommunityFactory $community, ChannelFactory $channelFactory, UserDefinedChannel $channel, App $app, TimelineFactory $timeline, SystemMessages $systemMessages, Mode $mode, Conversation $conversation, App\Page $page, IHandleUserSessions $session, Database $database, IManagePersonalConfigValues $pConfig, IManageConfigValues $config, ICanCache $cache, L10n $l10n, App\BaseURL $baseUrl, App\Arguments $args, LoggerInterface $logger, Profiler $profiler, Response $response, array $server, array $parameters = [])
	{
		parent::__construct($userDefinedChannel, $network, $community, $channelFactory, $channel, $app, $timeline, $systemMessages, $mode, $conversation, $page, $session, $database, $pConfig, $config, $cache, $l10n, $baseUrl, $args, $logger, $profiler, $response, $server, $parameters);

		$this->lock = $lock;
	}

	protected function rawContent(array $request = [])
	{
		if (!$this->session->getLocalUserId()) {
			System::exit();
		}

		if (!empty($request['ping'])) {
			$request = $this->getTimelineRequestBySession();
		}

		if (!isset($request['p']) || !isset($request['item'])) {
			System::exit();
		}

		$this->parseRequest($request);

		if ($this->force || !is_null($this->maxId)) {
			System::httpExit('');
		}

		$lockkey = 'network-ping-' . $this->session->getLocalUserId();
		if (!$this->lock->acquire($lockkey, 0)) {
			$this->logger->debug('Ping-1-lock', ['uid' => $this->session->getLocalUserId()]);
			System::httpExit('');
		}

		$this->setPing(true);
		$this->itemsPerPage = 100;

		if ($this->channel->isTimeline($this->selectedTab) || $this->userDefinedChannel->isTimeline($this->selectedTab, $this->session->getLocalUserId())) {
			$items = $this->getChannelItems($request, $this->session->getLocalUserId());
		} elseif ($this->community->isTimeline($this->selectedTab)) {
			$items = $this->getCommunityItems();
		} else {
			$items = $this->getItems();
		}
		$this->lock->release($lockkey);
		$count = count($items);
		System::httpExit(($count < 100) ? $count : '99+');
	}
}
