<?php
/**
 * Social Cloud
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

namespace OCA\Socialcloud\Controller;

use OCA\Socialcloud\Service\HttpService;
use \OCA\Socialcloud\Service\MiscService;
use \OCA\Socialcloud\Service\ConfigService;
use OC\AppFramework\Http;
use OCA\Socialcloud\Service\MoodService;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\IL10N;
use OCP\ILogger;
use OCP\IRequest;

class ToolsController extends Controller {

	/** @var string */
	private $userId;
	/** @var IL10N */
	private $l10n;
	/** @var HttpService */
	private $httpService;
	/** @var MiscService */
	private $miscService;

	public function __construct(
		$appName,
		IRequest $request,
		$userId,
		IL10N $l10n,
		HttpService $httpService,
		MiscService $miscService
	) {
		parent::__construct($appName, $request);

		$this->userId = $userId;
		$this->l10n = $l10n;
		$this->httpService = $httpService;
		$this->miscService = $miscService;
	}


	/**
	 * @NoAdminRequired
	 *
	 * @param $url
	 *
	 * @return DataResponse
	 */
	public function dataFromUrl($url) {

		$url =
			'https://www.reddit.com/r/EarthPorn/comments/61pubm/the_arctic_is_the_perfect_place_to_go_for_a/';
		try {
			$data = $this->httpService->getMetaFromWebsite($url);

			return NavigationController::success(['url' => $url, 'result' => $data]);
		} catch (\Exception $e) {
			$error = $e->getMessage();
		}

		return NavigationController::fail(
			['url' => $url, 'error' => $error]
		);


	}

}