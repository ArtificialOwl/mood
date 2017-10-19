<?php


namespace OCA\Mood\Activity;

use OCA\Circles\Api\v1\Circles;
use OCA\Circles\Model\Circle;
use OCA\Circles\Model\SharingFrame;
use OCP\Activity\IEvent;
use OCP\Activity\IManager;
use OCP\Activity\IProvider;
use OCP\IL10N;
use OCP\IURLGenerator;

class Provider implements IProvider {

	/** @var IL10N */
	protected $l10n;

	/** @var IURLGenerator */
	protected $url;

	/** @var IManager */
	protected $activityManager;

	public function __construct(IURLGenerator $url, IManager $activityManager, IL10N $l10n) {
		$this->url = $url;
		$this->activityManager = $activityManager;
		$this->l10n = $l10n;
	}


	/**
	 * @param string $lang
	 * @param IEvent $event
	 * @param IEvent|null $previousEvent
	 *
	 * @return IEvent
	 * @since 11.0.0
	 */
	public function parse($lang, IEvent $event, IEvent $previousEvent = null) {

		if ($event->getApp() !== 'mood') {
			throw new \InvalidArgumentException();
		}

		$event->setIcon(
			$this->url->getAbsoluteURL($this->url->imagePath('mood', 'mood_black.svg'))
		);

		switch ($event->getSubject()) {
			case 'mood_item':
				$this->parseMoodItem($event);

				return $event;
		}

		throw new \InvalidArgumentException();
	}


	/**
	 * @param IEvent $event
	 */
	private function parseMoodItem(IEvent &$event) {
		$params = $event->getSubjectParameters();
		if (!key_exists('share', $params)) {
			throw new \InvalidArgumentException();
		}

		$frame = SharingFrame::fromJSON($params['share']);
		if ($frame === null) {
			throw new \InvalidArgumentException();
		}

		$this->parseActivityHeader($event, $frame);
		$this->parseMoodPayload($event, $frame->getPayload());
	}


	/**
	 * @param IEvent $event
	 * @param $mood
	 */
	private function parseMoodPayload(IEvent &$event, $mood) {

		if (key_exists('website', $mood)) {
			$event->setRichMessage(
				$mood['text'] . '{opengraph}',
				['opengraph' => $this->generateOpenGraphParameter('_id_', $mood['website'])]
			);
		} else {
			$event->setRichMessage(htmlspecialchars($mood['text']));
		}

	}


	/**
	 * @param IEvent $event
	 * @param SharingFrame $frame
	 */
	private function parseActivityHeader(IEvent &$event, SharingFrame $frame) {

		$data = [
			'author'  => Circles::generateUserParameter($frame),
			'circles' => Circles::generateCircleParameter($frame)
		];

		if ($this->parseActivityHeaderAsAuthor($event, $frame, $data)) {
			return;
		}

		if ($frame->getCircle()->getType() === Circle::CIRCLES_PERSONAL) {
			$event->setRichSubject($this->l10n->t('{author} shared a mood with you'), $data);

			return;
		}

		$event->setRichSubject($this->l10n->t('{author} shared a mood with {circles}'), $data);
	}

	/**
	 * @param IEvent $event
	 * @param SharingFrame $frame
	 * @param array $data
	 *
	 * @return bool
	 */
	private function parseActivityHeaderAsAuthor(IEvent &$event, SharingFrame $frame, array $data) {

		if ($frame->getAuthor() === $this->activityManager->getCurrentUserId()
			&& $frame->getCloudId() === null
		) {
			$event->setRichSubject($this->l10n->t('You shared a mood with {circles}'), $data);

			return true;
		}

		return false;
	}


	/**
	 * @param $id
	 * @param $website
	 *
	 * @return array
	 */
	private function generateOpenGraphParameter($id, $website) {
		return [
			'type'        => 'open-graph',
			'id'          => $id,
			'name'        => $website['title'],
			'description' => $website['description'],
			'website'     => $website['website'],
			'thumb'       => \OC::$server->getURLGenerator()
										 ->linkToRoute('mood.Tools.binFromExternalImage') . '?url='
							 . rawurlencode($website['thumb']),
			'link'        => $website['url']
		];
	}


}
