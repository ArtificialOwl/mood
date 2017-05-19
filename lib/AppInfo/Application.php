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

namespace OCA\Mood\AppInfo;

use \OCA\Mood\Controller\MoodController;
use OCA\Mood\Controller\ToolsController;
use \OCA\Mood\Service\ConfigService;

use OCA\Mood\Service\HttpService;
use \OCA\Mood\Service\MiscService;
use OCA\Mood\Service\MoodService;
use OCP\AppFramework\App;
use OCP\Util;

class Application extends App {

	/** @var string */
	private $appName;

	/**
	 * @param array $params
	 */
	public function __construct(array $params = array()) {
		parent::__construct('mood', $params);

		$container = $this->getContainer();
		$this->appName = $container->query('AppName');

		/**
		 * Services
		 */
		$container->registerService(
			'MiscService', function($c) {
			return new MiscService($c->query('Logger'), $c->query('AppName'));
		}
		);


		$container->registerService(
			'ConfigService', function($c) {
			return new ConfigService(
				$c->query('AppName'), $c->query('CoreConfig'), $c->query('UserId'),
				$c->query('MiscService')
			);
		}
		);

		$container->registerService(
			'MoodService', function($c) {
			return new MoodService(
				$c->query('ActivityManager'), $c->query('HttpService'), $c->query('MiscService')
			);
		}
		);

		$container->registerService(
			'HttpService', function($c) {
			return new HttpService(
				$c->query('MiscService')
			);
		}
		);

		/**
		 * Controllers
		 */
		$container->registerService(
			'MoodController', function($c) {
			return new MoodController(
				$c->query('AppName'), $c->query('Request'), $c->query('UserId'), $c->query('L10N'),
				$c->query('MoodService'),
				$c->query('MiscService')
			);
		}
		);


		$container->registerService(
			'ToolsController', function($c) {
			return new ToolsController(
				$c->query('AppName'), $c->query('Request'), $c->query('UserId'), $c->query('L10N'),
				$c->query('HttpService'),
				$c->query('MiscService')
			);
		}
		);


		// Translates
		$container->registerService(
			'L10N', function($c) {
			return $c->query('ServerContainer')
					 ->getL10N($c->query('AppName'));
		}
		);

		/**
		 * Core
		 */
		$container->registerService(
			'Logger', function($c) {
			return $c->query('ServerContainer')
					 ->getLogger();
		}
		);
		$container->registerService(
			'CoreConfig', function($c) {
			return $c->query('ServerContainer')
					 ->getConfig();
		}
		);

		$container->registerService(
			'UserId', function($c) {
			$user = $c->query('ServerContainer')
					  ->getUserSession()
					  ->getUser();

			return is_null($user) ? '' : $user->getUID();
		}
		);


		$container->registerService(
			'ActivityManager', function($c) {
			return $c->query('ServerContainer')
					 ->getActivityManager();
		}
		);


		$container->registerService(
			'UserManager', function($c) {
			return $c->query('ServerContainer')
					 ->getUserManager();
		}
		);

	}


	public function registerToActivity() {
		if (!\OCP\App::isEnabled('circles')) {
			$this->getContainer()
				 ->query('MiscService')
				 ->log("mood needs circles");

			return;
		}

		\OC::$server->getEventDispatcher()
					->addListener(
						'OCA\Activity::loadAdditionalScripts', function($event) {
						\OCP\Util::addScript('circles', 'circles');
						\OCP\Util::addScript('mood', 'mood');
						\OCP\Util::addScript('mood', 'mood.app');
						\OCP\Util::addScript('mood', 'mood.app.elements');
						\OCP\Util::addScript('mood', 'mood.app.actions');
						\OCP\Util::addScript('mood', 'mood.app.navigation');

						\OCP\Util::addStyle('mood', 'navigate');
					}
					);
	}
}

