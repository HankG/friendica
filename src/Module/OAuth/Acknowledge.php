<?php

// Copyright (C) 2010-2024, the Friendica project
// SPDX-FileCopyrightText: 2010-2024 the Friendica project
//
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace Friendica\Module\OAuth;

use Friendica\Core\Renderer;
use Friendica\DI;
use Friendica\Module\BaseApi;
use Friendica\Module\Special\HTTPException;
use Psr\Http\Message\ResponseInterface;

/**
 * Acknowledgement of OAuth requests
 */
class Acknowledge extends BaseApi
{
	public function run(HTTPException $httpException, array $request = [], bool $scopecheck = true): ResponseInterface
	{
		return parent::run($httpException, $request, false);
	}

	protected function post(array $request = [])
	{
		DI::session()->set('oauth_acknowledge', true);
		DI::appHelper()->redirect(DI::session()->get('return_path'));
	}

	protected function content(array $request = []): string
	{
		DI::session()->set('return_path', 'oauth/authorize?' . $request['return_authorize']);

		$o = Renderer::replaceMacros(Renderer::getMarkupTemplate('oauth_authorize.tpl'), [
			'$title'     => DI::l10n()->t('Authorize application connection'),
			'$app'       => ['name' => $_REQUEST['application'] ?? ''],
			'$authorize' => DI::l10n()->t('Do you want to authorize this application to access your posts and contacts, and/or create new posts for you?'),
			'$yes'       => DI::l10n()->t('Yes'),
			'$no'        => DI::l10n()->t('No'),
		]);

		return $o;
	}
}
