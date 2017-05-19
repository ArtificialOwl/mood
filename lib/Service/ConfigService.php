<?php
/**
 * Mood
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Maxence Lange <maxence@pontapreta.net>
 * @copyright 2017
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
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *
 */

namespace OCA\Mood\Service;

use OCP\IConfig;

class ConfigService {


	private $defaults = [];

	private $appName;

	private $config;

	private $miscService;

	public function __construct($appName, IConfig $config, $userId, MiscService $miscService) {
		$this->appName = $appName;
		$this->config = $config;
		$this->userId = $userId;
		$this->miscService = $miscService;
	}

	/**
	 * Get a user value by key
	 *
	 * @param string $key
	 *
	 * @return string
	 */
	public function getUserValue($key) {
		return $this->config->getUserValue($this->userId, $this->appName, $key);
	}

	/**
	 * Set a user value by key
	 *
	 * @param string $key
	 * @param string $value
	 *
	 * @return string
	 */
	public function setUserValue($key, $value) {
		return $this->config->setUserValue($this->userId, $this->appName, $key, $value);
	}

}
