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


use OCA\Circles\Api\Circles;
use OCP\Activity\IManager;

class MoodService {

	/** @var HttpService */
	private $httpService;

	/** @var IManager */
	private $activityManager;

	/** @var MiscService */
	private $miscService;

	/**
	 * MoodService constructor.
	 *
	 * @param HttpService $httpService
	 * @param MiscService $miscService
	 * @param IManager $activityManager
	 */
	public function __construct(
		IManager $activityManager, HttpService $httpService, MiscService $miscService
	) {
		$this->activityManager = $activityManager;
		$this->httpService = $httpService;
		$this->miscService = $miscService;
	}


	public function shareToCircle(int $circleId, array $item) {
		Circles::shareToCircle($circleId, 'mood', '', $item, 'OCA\Mood\Circles\Broadcaster');
	}

}
