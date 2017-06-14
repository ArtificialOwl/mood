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

use OC\AppFramework\Http;
use OCA\Mood\Service\HttpService;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\DataDisplayResponse;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Http\FileDisplayResponse;
use OCP\IRequest;

class ToolsController extends Controller {

	/** @var HttpService */
	private $httpService;

	public function __construct($appName, IRequest $request, HttpService $httpService) {
		parent::__construct($appName, $request);

		$this->httpService = $httpService;
	}


	/**
	 * @NoAdminRequired
	 *
	 * @param $url
	 *
	 * @return DataResponse
	 */
	public function dataFromUrl($url) {

		try {
			$data = $this->httpService->getMetaFromWebsite($url);

			return self::success(['url' => $url, 'data' => $data]);
		} catch (\Exception $e) {
			$error = $e->getMessage();
		}

		return self::fail(
			['url' => $url, 'error' => $error]
		);
	}


	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * @param $url
	 *
	 * @return DataDisplayResponse|DataResponse|FileDisplayResponse
	 */
	public function binFromExternalImage($url) {
		try {

			$image = HttpService::file_get_contents_curl($url, true);
			$response =
				new DataDisplayResponse(
					$image, Http::STATUS_OK, ['Content-Type' => 'image/jpeg']
				);

			return $response;
		} catch (\Exception $e) {
			return new DataResponse([], Http::STATUS_NOT_FOUND);
		}


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