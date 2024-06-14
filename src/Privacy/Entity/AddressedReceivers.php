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

namespace Friendica\Privacy\Entity;

use Friendica\BaseEntity;

class AddressedReceivers extends BaseEntity
{
	protected array $to         = [];
	protected array $cc         = [];
	protected array $bcc        = [];
	protected array $audience   = [];
	protected array $attributed = [];

	public function __construct(array $to = [], array $cc = [], array $bcc = [], array $audience = [], array $attributed = [])
	{
		$this->to         = $to;
		$this->cc         = $cc;
		$this->bcc        = $bcc;
		$this->audience   = $audience;
		$this->attributed = $attributed;
	}

	public function isEmpty(): bool
	{
		return empty($this->to) && empty($this->cc) && empty($this->bcc) && empty($this->audience) && empty($this->attributed);
	}
}
