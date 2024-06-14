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

namespace Friendica\Network\HTTPClient\Client;

/**
 * This class contains a list of request types that are set in the user agent string
 */
class HttpClientRequest
{
	public const ACTIVITYPUB     = 'ActivityPub/1';
	public const CONTACTINFO     = 'ContactInfo/1';
	public const CONTACTDISCOVER = 'ContactDiscover/1';
	public const CONTACTVERIFIER = 'ContactVerifier/1';
	public const CONTENTTYPE     = 'ContentTypeChecker/1';
	public const DFRN            = 'DFRN/1';
	public const DIASPORA        = 'Diaspora/1';
	public const FEEDFETCHER     = 'FeedFetcher/1';
	public const MAGICAUTH       = 'MagicAuth/1';
	public const MEDIAPROXY      = 'MediaProxy/1';
	public const MEDIAVERIFIER   = 'MediaVerifier/1';
	public const OSTATUS         = 'OStatus/1';
	public const SALMON          = 'Salmon/1';
	public const SERVERINFO      = 'ServerInfo/1';
	public const SERVERDISCOVER  = 'ServerDiscover/1';
	public const SITEINFO        = 'SiteInfo/1';
	public const PUBSUB          = 'PubSub/1';
	public const URLRESOLVER     = 'URLResolver/1';
	public const URLVERIFIER     = 'URLVerifier/1';
}
