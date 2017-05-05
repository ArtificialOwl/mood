<?php

namespace OCA\Mood\Circles;

use OCA\Circles\IBroadcaster;
use OCA\Circles\Model\Share;
use OCA\Mood\AppInfo\Application;
use OCA\Mood\Service\MiscService;
use OCP\Activity\IManager;

class Broadcaster implements IBroadcaster {

	/** @var IManager */
	private $activityManager;

	/** @var MiscService */
	private $miscService;


	public function init() {
		$app = new Application();
		$c = $app->getContainer();

		$this->activityManager = $c->query('ActivityManager');
		$this->miscService = $c->query('MiscService');
	}

	/**
	 * @param string $userId
	 * @param Share $share
	 *
	 * @return bool
	 */
	public function broadcast(string $userId, Share $share) {

		$this->miscService->log("_Share: " . var_export($share, true));

		try {
			$event = $this->activityManager->generateEvent();
			$event->setApp('mood');
			$event->setType('mood');
			$event->setAffectedUser($userId);
			$event->setAuthor($share->getAuthor());

			$event->setSubject('mood_item', ['share' => json_encode($share)]);

			$this->activityManager->publish($event);

			return true;
		} catch (\Exception $e) {
		}

		return false;
	}

}