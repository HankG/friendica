<?php

// Copyright (C) 2010-2024, the Friendica project
// SPDX-FileCopyrightText: 2010-2024 the Friendica project
//
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace Friendica\Object\EMail;

use JsonSerializable;

/**
 * Interface for a single mail, which can be send through Emailer::send()
 *
 * @see Emailer::send()
 */
interface IEmail extends JsonSerializable
{
	/**
	 * Gets the senders name for this email
	 *
	 * @return string
	 */
	function getFromName();

	/**
	 * Gets the senders email address for this email
	 *
	 * @return string
	 */
	function getFromAddress();

	/**
	 * Gets the UID of the sender of this email
	 *
	 * @return int|null
	 */
	function getRecipientUid();

	/**
	 * Gets the reply-to address for this email
	 *
	 * @return string
	 */
	function getReplyTo();

	/**
	 * Gets the senders email address
	 *
	 * @return string
	 */
	function getToAddress();

	/**
	 * Gets the subject of this email
	 *
	 * @return string
	 */
	function getSubject();

	/**
	 * Gets the message body of this email (either html or plaintext)
	 *
	 * @param boolean $plain True, if returned as plaintext
	 *
	 * @return string
	 */
	function getMessage(bool $plain = false): string;

	/**
	 * Gets the additional mail header array
	 *
	 * @return string[][]
	 */
	function getAdditionalMailHeader();

	/**
	 * Gets the additional mail header as string - EOL separated
	 *
	 * @return string
	 */
	function getAdditionalMailHeaderString();

	/**
	 * Returns the current email with a new recipient
	 *
	 * @param string $address The email of the recipient
	 * @param int    $uid   The (optional) UID of the recipient for further infos
	 *
	 * @return static
	 */
	function withRecipient(string $address, int $uid);

	/**
	 * @param string $plaintext a new plaintext message for this email
	 * @param string $html      a new html message for this email (optional)
	 *
	 * @return static
	 */
	function withMessage(string $plaintext, string $html = null);

	/**
	 * @return string
	 */
	function __toString();
}
