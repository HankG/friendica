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

class AclReceivers extends BaseEntity
{
	protected array $allowContacts   = [];
	protected array $allowCircles = [];
	protected array $denyContacts    = [];
	protected array $denyCircles  = [];

	public function __construct(array $allowContacts = [], array $allowCircles = [], array $denyContacts = [], array $denyCircles = [])
	{
		$this->allowContacts = $allowContacts;
		$this->allowCircles  = $allowCircles;
		$this->denyContacts  = $denyContacts;
		$this->denyCircles   = $denyCircles;
	}

	public function isEmpty(): bool
	{
		return empty($this->allowContacts) && empty($this->allowCircles) && empty($this->denyContacts) && empty($this->denyCircles);
	}
}
