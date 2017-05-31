<?php

namespace OCA\Mood\Circles;

use OCA\Circles\IBroadcaster;
use OCA\Circles\Model\SharingFrame;
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
	 * @param SharingFrame $frame
	 *
	 * @return bool
	 */
	public function broadcast($userId, SharingFrame $frame) {

		try {
			$event = $this->activityManager->generateEvent();
			$event->setApp('mood');
			$event->setType('mood');
			$event->setAffectedUser($userId);
			$event->setAuthor($frame->getAuthor());
			$event->setSubject('mood_item', ['share' => json_encode($frame)]);

			$this->activityManager->publish($event);

			return true;
		} catch (\Exception $e) {
			return false;
		}
	}

}