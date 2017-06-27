<?php

namespace OCA\Mood\Circles;

use OCA\Circles\IBroadcaster;
use OCA\Circles\Model\SharingFrame;
use OCA\Mood\AppInfo\Application;
use OCP\Activity\IManager;

class Broadcaster implements IBroadcaster {

	/** @var IManager */
	private $activityManager;

	/**
	 * {@inheritdoc}
	 */
	public function init() {
		$app = new Application();
		$c = $app->getContainer();

		$this->activityManager = $c->query('ActivityManager');
	}

	/**
	 * {@inheritdoc}
	 */
	public function createShareToUser(SharingFrame $frame, $userId) {

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


	/**
	 * {@inheritdoc}
	 */
	public function deleteShareToUser(SharingFrame $frame, $userId) {
		return true;
	}


	/**
	 * {@inheritdoc}
	 */
	public function editShareToUser(SharingFrame $frame, $userId) {
		return true;
	}


	/**
	 * {@inheritdoc}
	 */
	public function createShareToCircle(SharingFrame $frame) {
		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function deleteShareToCircle(SharingFrame $frame) {
		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function editShareToCircle(SharingFrame $frame) {
		return true;
	}


}