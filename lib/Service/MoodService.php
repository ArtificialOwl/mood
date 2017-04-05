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


use OCA\Shares\Exceptions\MoodUnknownType;

class MoodService {

	private $httpService;
	private $miscService;

	public function __construct(HttpService $httpService, MiscService $miscService) {
		$this->httpService = $httpService;
		$this->miscService = $miscService;
	}


	public function createMood($mood, $shares) {

		$share = $this->shareMood($mood, $shares);

		return $share;
	}


	public function createMoodText($text) {
	}


	public function createMoodUrl($url) {
		try {
			return $this->httpService->getMetaFromWebsite($url);
		} catch (\Exception $e) {
			throw $e;
		}
	}


	public function shareMood($data, $shares) {

		return true;
	}

}
