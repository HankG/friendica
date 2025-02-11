<?php

// Copyright (C) 2010-2024, the Friendica project
// SPDX-FileCopyrightText: 2010-2024 the Friendica project
//
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace Friendica\Network\HTTPClient\Client;

use GuzzleHttp\RequestOptions;

/**
 * This class contains a list of possible HTTPClient request options.
 */
class HttpClientOptions
{
	/**
	 * accept_content: (array) supply Accept: header with 'accept_content' as the value
	 */
	const ACCEPT_CONTENT = 'accept_content';
	/**
	 * timeout: (int) out in seconds, default system config value or 60 seconds
	 */
	const TIMEOUT = RequestOptions::TIMEOUT;
	/**
	 * cookiejar: (string) path to cookie jar file
	 */
	const COOKIEJAR = 'cookiejar';
	/**
	 * headers: (array) header array
	 */
	const HEADERS = RequestOptions::HEADERS;
	/**
	 * header: (array) header array (legacy version)
	 */
	const LEGACY_HEADER = 'header';
	/**
	 * content_length: (int) maximum File content length
	 */
	const CONTENT_LENGTH = 'content_length';
	/**
	 * Request: (string) Type of request (ActivityPub, Diaspora, server discovery, ...)
	 */
	const REQUEST = 'request';
	/**
	 * verify: (bool|string, default=true) Describes the SSL certificate
	 */
	const VERIFY = 'verify';

	/**
	 * body: (string) Setting the body for sending data
	 */
	const BODY = RequestOptions::BODY;
	/**
	 * form_params: (array) Associative array of form field names to values
	 */
	const FORM_PARAMS = RequestOptions::FORM_PARAMS;
	/**
	 * auth: (array) Authentication settings for specific requests
	 */
	const AUTH = RequestOptions::AUTH;
}
