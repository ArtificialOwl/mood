<?php

namespace OCA\Mood\Circles;

use OCA\Circles\IBroadcaster;
use OCA\Circles\Model\Circle;
use OCA\Circles\Model\Member;
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
	public function end() {
	}

	/**
	 * {@inheritdoc}
	 */
	public function createShareToMember(SharingFrame $frame, Member $member) {
		switch ($member->getType()) {
			case Member::TYPE_USER:
				return $this->generateLocalEvent($frame, $member);

			case Member::TYPE_MAIL:
				return $this->sendMailEvent();
		}

		return false;
	}


	/**
	 * generateLocalEvent();
	 *
	 * generate an event using the ActivityManager.
	 *
	 * @param SharingFrame $frame
	 * @param Member $member
	 *
	 * @return bool
	 */
	private function generateLocalEvent(SharingFrame $frame, Member $member) {

		try {
			$event = $this->activityManager->generateEvent();
			$event->setApp('mood');
			$event->setType('mood');
			$event->setAffectedUser($member->getUserId());
			$event->setAuthor($frame->getAuthor());
			$event->setSubject('mood_item', ['share' => json_encode($frame)]);

			$this->activityManager->publish($event);

			return true;
		} catch (\Exception $e) {
			return false;
		}
	}


	private function sendMailEvent() {
		return false;
	}

	/**
	 * {@inheritdoc}
	 */
	public function deleteShareToMember(SharingFrame $frame, Member $member) {
		return true;
	}


	/**
	 * {@inheritdoc}
	 */
	public function editShareToMember(SharingFrame $frame, Member $member) {
		return true;
	}


	/**
	 * {@inheritdoc}
	 */
	public function createShareToCircle(SharingFrame $frame, Circle $circle) {
		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function deleteShareToCircle(SharingFrame $frame, Circle $circle) {
		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function editShareToCircle(SharingFrame $frame, Circle $circle) {
		return true;
	}
}