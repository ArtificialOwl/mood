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

namespace OCA\Socialcloud\AppInfo;

use \OCA\Socialcloud\Controller\NavigationController;
use OCA\Socialcloud\Controller\ToolsController;
use \OCA\Socialcloud\Service\ConfigService;

use OCA\Socialcloud\Service\HttpService;
use \OCA\Socialcloud\Service\MiscService;
use OCA\Socialcloud\Service\MoodService;
use OCP\AppFramework\App;
use OCP\Util;

class Application extends App {

	/** @var string */
	private $appName;

	/**
	 * @param array $params
	 */
	public function __construct(array $params = array()) {
		parent::__construct('socialcloud', $params);

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
				$c->query('HttpService'), $c->query('MiscService')
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
//		$container->registerService(
//			'SettingsController', function ($c) {
//			return new SettingsController(
//				$c->query('AppName'), $c->query('Request'), $c->query('ConfigService'),
//				$c->query('MiscService')
//			);
//		}
//		);

		$container->registerService(
			'NavigationController', function($c) {
			return new NavigationController(
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


		/**
		 * Mapper
		 */
//		$container->registerService(
//			'DepositionFilesMapper', function ($c) {
//			return new DepositionFilesMapper(
//				$c->query('ServerContainer')
//				  ->getDatabaseConnection()
//			);
//		}
//		);

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
			'UserManager', function($c) {
			return $c->query('ServerContainer')
					 ->getUserManager();
		}
		);
	}


	public function registerNavigation() {

		$this->getContainer()
			 ->getServer()
			 ->getNavigationManager()
			 ->add(
				 function() {
					 return [
						 'id'    => $this->appName,
						 'order' => 5,
						 'href'  => \OC::$server->getURLGenerator()
												->linkToRoute('socialcloud.Navigation.navigate'),
						 'icon'  => \OC::$server->getURLGenerator()
												->imagePath($this->appName, 'socialcloud.svg'),
						 'name'  => \OC::$server->getL10N($this->appName)
												->t('Social Cloud')
					 ];
				 }
			 );
	}


//
//	public function registerSettingsAdmin() {
//		\OCP\App::registerAdmin(
//			$this->getContainer()
//				 ->query('AppName'), 'lib/admin'
//		);
//	}
}

