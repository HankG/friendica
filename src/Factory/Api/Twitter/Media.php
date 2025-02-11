<?php

// Copyright (C) 2010-2024, the Friendica project
// SPDX-FileCopyrightText: 2010-2024 the Friendica project
//
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace Friendica\Factory\Api\Twitter;

use Friendica\App\BaseURL;
use Friendica\BaseFactory;
use Friendica\Network\HTTPException;
use Friendica\Model\Post;
use Psr\Log\LoggerInterface;

class Media extends BaseFactory
{
	/** @var BaseURL */
	private $baseUrl;

	public function __construct(LoggerInterface $logger, BaseURL $baseURL)
	{
		parent::__construct($logger);

		$this->baseUrl = $baseURL;
	}

	/**
	 * @param int $uriId Uri-ID of the attachments
	 * @param string $text
	 *
	 * @return array
	 * @throws HTTPException\InternalServerErrorException
	 */
	public function createFromUriId(int $uriId, string $text): array
	{
		$attachments = [];
		foreach (Post\Media::getByURIId($uriId, [Post\Media::AUDIO, Post\Media::IMAGE, Post\Media::VIDEO]) as $attachment) {
			if ($attachment['type'] == Post\Media::IMAGE) {
				$url = Post\Media::getUrlForId($attachment['id']);
			} elseif (!empty($attachment['preview'])) {
				$url = Post\Media::getPreviewUrlForId($attachment['id']);
			} else {
				$url = $attachment['url'];
			}

			$indices = [];

			$object        = new \Friendica\Object\Api\Twitter\Media($attachment, $url, $indices);
			$attachments[] = $object->toArray();
		}

		return $attachments;
	}
}
