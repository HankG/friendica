<?php

// Copyright (C) 2010-2024, the Friendica project
// SPDX-FileCopyrightText: 2010-2024 the Friendica project
//
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace Friendica\Object\Search;

use Friendica\Model\Search;
use Psr\Http\Message\UriInterface;

/**
 * A search result for contact searching
 *
 * @see Search for details
 */
class ContactResult implements IResult
{
	/**
	 * @var int
	 */
	private $cid;
	/**
	 * @var int
	 */
	private $pCid;
	/**
	 * @var string
	 */
	private $name;
	/**
	 * @var string
	 */
	private $addr;
	/**
	 * @var string
	 */
	private $item;
	/**
	 * @var UriInterface
	 */
	private $url;
	/**
	 * @var string
	 */
	private $photo;
	/**
	 * @var string
	 */
	private $tags;
	/**
	 * @var string
	 */
	private $network;

	/**
	 * @return int
	 */
	public function getCid()
	{
		return $this->cid;
	}

	/**
	 * @return int
	 */
	public function getPCid()
	{
		return $this->pCid;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getAddr()
	{
		return $this->addr;
	}

	/**
	 * @return string
	 */
	public function getItem()
	{
		return $this->item;
	}

	/**
	 * @return UriInterface
	 */
	public function getUrl(): UriInterface
	{
		return $this->url;
	}

	/**
	 * @return string
	 */
	public function getPhoto()
	{
		return $this->photo;
	}

	/**
	 * @return string
	 */
	public function getTags()
	{
		return $this->tags;
	}

	/**
	 * @return string
	 */
	public function getNetwork()
	{
		return $this->network;
	}

	/**
	 * @param string $name
	 * @param string $addr
	 * @param string $item
	 * @param UriInterface $url
	 * @param string $photo
	 * @param string $network
	 * @param int    $cid
	 * @param int    $pCid
	 * @param string $tags
	 */
	public function __construct($name, $addr, $item, UriInterface $url, $photo, $network, $cid = 0, $pCid = 0, $tags = '')
	{
		$this->name    = $name;
		$this->addr    = $addr;
		$this->item    = $item;
		$this->url     = $url;
		$this->photo   = $photo;
		$this->network = $network;

		$this->cid  = $cid;
		$this->pCid = $pCid;
		$this->tags = $tags;
	}
}
