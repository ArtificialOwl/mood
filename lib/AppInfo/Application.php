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

use OCA\Circles\Api\v1\Circles;
use OCA\Mood\Controller\MoodController;
use OCA\Mood\Controller\ToolsController;
use OCA\Mood\Service\HttpService;
use OCP\AppFramework\App;
use OCP\AppFramework\IAppContainer;
use OCP\Notification\IApp;
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

		$this->registerCore($container);
		$this->registerServices($container);
		$this->registerControllers($container);
	}


	/**
	 * @param IAppContainer $container
	 */
	public function registerServices(IAppContainer $container) {

		$container->registerService(
			'HttpService', function() {
			return new HttpService();
		}
		);
	}


	/**
	 * @param IAppContainer $container
	 */
	public function registerControllers(IAppContainer $container) {

		$container->registerService(
			'MoodController', function(IAppContainer $c) {
			return new MoodController($c->query('AppName'), $c->query('Request'));
		}
		);


		$container->registerService(
			'ToolsController', function(IAppContainer $c) {
			return new ToolsController(
				$c->query('AppName'), $c->query('Request'), $c->query('HttpService')
			);
		}
		);

	}


	/**
	 * @param IAppContainer $container
	 */
	public function registerCore(IAppContainer $container) {

		$container->registerService(
			'L10N', function(IAppContainer $c) {
			return $c->query('ServerContainer')
					 ->getL10N($c->query('AppName'));
		}
		);

		$container->registerService(
			'ActivityManager', function(IAppContainer $c) {
			return $c->query('ServerContainer')
					 ->getActivityManager();
		}
		);
	}


	/**
	 *
	 */
	public function registerToActivity() {
		if (!\OCP\App::isEnabled('circles')) {
			\OC::$server->getLogger()
						->log(2, 'mood needs circles');

			return;
		}

		\OC::$server->getEventDispatcher()
					->addListener(
						'OCA\Activity::loadAdditionalScripts', function() {
						Circles::addJavascriptAPI();
						Util::addScript('mood', 'mood');
						Util::addScript('mood', 'mood.app');
						Util::addScript('mood', 'mood.app.elements');
						Util::addScript('mood', 'mood.app.actions');
						Util::addScript('mood', 'mood.app.navigation');

						Util::addStyle('mood', 'navigate');
					}
					);
	}
}

