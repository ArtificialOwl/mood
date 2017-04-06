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

namespace OCA\Mood\Controller;

use \OCA\Mood\Service\MiscService;
use \OCA\Mood\Service\ConfigService;
use OC\AppFramework\Http;
use OCA\Mood\Service\MoodService;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\IL10N;
use OCP\ILogger;
use OCP\IRequest;

class MoodController extends Controller {

	/** @var string */
	private $userId;
	/** @var IL10N */
	private $l10n;
	/** @var MoodService */
	private $moodService;
	/** @var MiscService */
	private $miscService;

	public function __construct(
		$appName,
		IRequest $request,
		$userId,
		IL10N $l10n,
		MoodService $moodService,
		MiscService $miscService
	) {
		parent::__construct($appName, $request);

		$this->userId = $userId;
		$this->l10n = $l10n;
		$this->moodService = $moodService;
		$this->miscService = $miscService;
	}


	/**
	 * @NoAdminRequired
	 *
	 * @param $mObj
	 *
	 * @return DataResponse
	 */
	public function create($mObj) {

		$this->miscService->log("!!!! " . var_export($mObj, true));
		try {
			$result = $this->moodService->createMood($mObj);

			return self::success(['data' => $mObj, 'result' => $result]);
		} catch (\Exception $e) {
			$error = $e->getMessage();
		}

		return self::fail(
			['data' => $mObj, 'error' => $error]
		);


	}


	/**
	 * @param $data
	 *
	 * @return DataResponse
	 */
	public static function fail($data) {
		return new DataResponse(
			array_merge($data, array('status' => 0)),
			Http::STATUS_NON_AUTHORATIVE_INFORMATION
		);
	}

	/**
	 * @param $data
	 *
	 * @return DataResponse
	 */
	public static function success($data) {
		return new DataResponse(
			array_merge($data, array('status' => 1)),
			Http::STATUS_CREATED
		);
	}


}