<?php


namespace OCA\Mood\Activity;

use OCA\Circles\Api\v1\Circles;
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

		switch ($event->getSubject()) {
			case 'mood_item':
				$params = $event->getSubjectParameters();
				if (!key_exists('share', $params)) {
					throw new \InvalidArgumentException();
				}

				$event->setIcon(
					$this->url->getAbsoluteURL($this->url->imagePath('mood', 'mood_black.svg'))
				);

				$frame = SharingFrame::fromJSON($params['share']);

				if ($frame === null) {
					throw new \InvalidArgumentException();
				}
				$mood = $frame->getPayload();
				$this->parseActivityHeader($event, $frame);
				$this->parseMood($event, $mood);
				break;

			default:
				throw new \InvalidArgumentException();
		}

		return $event;
	}


	private function parseMood(IEvent &$event, $mood) {

		if (key_exists('website', $mood)) {
			$event->setRichMessage(
				$mood['text'] . '{opengraph}',
				['opengraph' => $this->generateOpenGraphParameter('_id_', $mood['website'])]
			);
		} else {
			$event->setRichMessage(htmlspecialchars($mood['text']));
		}

	}


	private function parseActivityHeader(IEvent &$event, SharingFrame $frame) {

		$this->activityManager->getCurrentUserId();

		if ($frame->getAuthor() === $this->activityManager->getCurrentUserId()
			&& $frame->getCloudId() === null
		) {

			$event->setRichSubject(
				$this->l10n->t('You shared a mood with {circles}'),
				['circles' => $this->generateCircleParameter($frame)]

			);

		} else {

			$author = $this->generateUserParameter($frame);
			$event->setRichSubject(
				$this->l10n->t(
					'{author} shared a mood with {circles}'
				), [
					'author'  => $author,
					'circles' => $this->generateCircleParameter($frame)
				]
			);
		}
	}


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


	private function generateCircleParameter(SharingFrame $frame) {
		return [
			'type' => 'circle',
			'id'   => $frame->getCircleId(),
			'name' => $frame->getCircleName(),
			'link' => Circles::generateLink($frame->getCircleId())
		];
	}


	/**
	 * @param SharingFrame $frame
	 *
	 * @return array
	 */
	private function generateUserParameter(SharingFrame $frame) {
		if ($frame->getCloudId() !== null) {
			$name = $frame->getAuthor() . '@' . $frame->getCloudId();
		} else {
			$name = \OC::$server->getUserManager()
								->get($frame->getAuthor())
								->getDisplayName();
		}

		return [
			'type' => 'user',
			'id'   => $frame->getAuthor(),
			'name' => $name
		];
	}
}
