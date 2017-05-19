<?php


namespace OCA\Mood\Activity;

use OCA\Circles\Model\Share;
use OCA\Mood\Service\MiscService;
use OCP\Activity\IEvent;
use OCP\Activity\IManager;
use OCP\Activity\IProvider;
use OCP\Comments\NotFoundException;
use OCP\IL10N;
use OCP\IURLGenerator;

class Provider implements IProvider {

	/** @var MiscService */
	protected $miscService;

	/** @var IL10N */
	protected $l10n;

	/** @var IURLGenerator */
	protected $url;

	/** @var IManager */
	protected $activityManager;

	public function __construct(
		IURLGenerator $url, IManager $activityManager, IL10N $l10n, MiscService $miscService
	) {
		$this->url = $url;
		$this->activityManager = $activityManager;
		$this->l10n = $l10n;
		$this->miscService = $miscService;
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
					$this->url->getAbsoluteURL($this->url->imagePath('mood', 'mood.svg'))
				);

				$share = Share::fromJSON($params['share']);
				$mood = $share->getItem();
				$this->parseActivityHeader($event, $share);
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
			$event->setParsedMessage($mood['text']);
		}


	}


	private function parseActivityHeader(IEvent &$event, Share $share) {

		$this->activityManager->getCurrentUserId();

		if ($share->getAuthor() === $this->activityManager->getCurrentUserId()) {

			$event->setParsedSubject(
				$this->l10n->t(
					'You shared a mood with %1$s', ['circle1, circle2']
				)
			)
				  ->setRichSubject(
					  $this->l10n->t(
						  'You shared a mood with {circles}'
					  ),
					  ['circles' => $this->generateCircleParameter($share)]

				  );

		} else {

			$author = $this->generateUserParameter($share->getAuthor());
			$event->setParsedSubject(
				$this->l10n->t(
					'%1$s shared a mood with %2$s', [
													  $author['name'],
													  'circle1, circle2'
												  ]
				)
			)
				  ->setRichSubject(
					  $this->l10n->t(
						  '{author} shared a mood with {circles}'
					  ), [
						  'author'  => $author,
						  'circles' => $this->generateCircleParameter($share)
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


	private function generateCircleParameter(Share $share) {
		return [
			'type' => 'circle',
			'id'   => $share->getCircleId(),
			'name' => $share->getCircleName(),
			'link' => \OC::$server->getURLGenerator()
								  ->linkToRoute('circles.Navigation.navigate')
					  . '#' . $share->getCircleId()
		];
	}


	private function generateUserParameter($uid) {
		return [
			'type' => 'user',
			'id'   => $uid,
			'name' => $uid,// FIXME Use display name
		];
	}
}
