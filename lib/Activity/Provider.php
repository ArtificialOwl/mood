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

//		$this->miscService->log(">>> " . var_export($event, true));
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
		$event->setParsedMessage($mood['text']);
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
//		$subjectParameters = $event->getSubjectParameters();
//			if ($subjectParameters[0] === $this->activityManager->getCurrentUserId()) {


//		$this->l10n->t(
//						'You commented on %1$s', [
//												   trim($subjectParameters[1], '/'),
//											   ]
//					)
//				)
//					  ->setRichSubject(
//						  $this->l10n->t('You commented on {file}'), [
//																	   'file' => $this->generateFileParameter(
//																		   $event->getObjectId(),
//																		   $subjectParameters[1]
//																	   ),
//																   ]
//					  );
	}

//	/**
//	 * @param IEvent $event
//	 *
//	 * @return IEvent
//	 * @throws \InvalidArgumentException
//	 * @since 11.0.0
//	 */
//	protected function parseShortVersion(IEvent $event) {
//		$subjectParameters = $event->getSubjectParameters();
//
//		if ($event->getSubject() === 'add_comment_subject') {
//			if ($subjectParameters[0] === $this->activityManager->getCurrentUserId()) {
//				$event->setParsedSubject($this->l10n->t('You commented'))
//					  ->setRichSubject($this->l10n->t('You commented'), []);
//			} else {
//				$author = $this->generateUserParameter($subjectParameters[0]);
//				$event->setParsedSubject($this->l10n->t('%1$s commented', [$author['name']]))
//					  ->setRichSubject(
//						  $this->l10n->t('{author} commented'), [
//																  'author' => $author,
//															  ]
//					  );
//			}
//		} else {
//			throw new \InvalidArgumentException();
//		}
//
//		return $event;
//	}

	/**
	 * @param IEvent $event
	 *
	 * @return IEvent
	 * @throws \InvalidArgumentException
	 * @since 11.0.0
	 */
	protected function parseLongVersion(IEvent $event) {
		$subjectParameters = $event->getSubjectParameters();

//		if ($event->getSubject() === 'add_comment_subject') {
//			if ($subjectParameters[0] === $this->activityManager->getCurrentUserId()) {
//				$event->setParsedSubject(
//					$this->l10n->t(
//						'You commented on %1$s', [
//												   trim($subjectParameters[1], '/'),
//											   ]
//					)
//				)
//					  ->setRichSubject(
//						  $this->l10n->t('You commented on {file}'), [
//																	   'file' => $this->generateFileParameter(
//																		   $event->getObjectId(),
//																		   $subjectParameters[1]
//																	   ),
//																   ]
//					  );
//			} else {
//				$author = $this->generateUserParameter($subjectParameters[0]);
//				$event->setParsedSubject(
//					$this->l10n->t(
//						'%1$s commented on %2$s', [
//													$author['name'],
//													trim($subjectParameters[1], '/'),
//												]
//					)
//				)
//					  ->setRichSubject(
//						  $this->l10n->t('{author} commented on {file}'), [
//																			'author' => $author,
//																			'file'   => $this->generateFileParameter(
//																				$event->getObjectId(
//																				),
//																				$subjectParameters[1]
//																			),
//																		]
//					  );
//			}
//		} else {
//			throw new \InvalidArgumentException();
//		}

		return $event;
	}

	protected function parseMessage(IEvent $event) {
		$messageParameters = $event->getMessageParameters();
		try {
//			$comment = $this->commentsManager->get((int)$messageParameters[0]);
//			$message = $comment->getMessage();
//			$message =
//				str_replace("\n", '<br />', str_replace(['<', '>'], ['&lt;', '&gt;'], $message));
//
//			$mentionCount = 1;
//			$mentions = [];
//			foreach ($comment->getMentions() as $mention) {
//				if ($mention['type'] !== 'user') {
//					continue;
//				}
//
//				$message = preg_replace(
//					'/(^|\s)(' . '@' . $mention['id'] . ')(\b)/',
//					//'${1}' . $this->regexSafeUser($mention['id'], $displayName) . '${3}',
//					'${1}' . '{mention' . $mentionCount . '}' . '${3}',
//					$message
//				);
//				$mentions['mention' . $mentionCount] = $this->generateUserParameter($mention['id']);
//				$mentionCount++;
//			}
//
//			$event->setParsedMessage($comment->getMessage())
//				  ->setRichMessage($message, $mentions);
		} catch (NotFoundException $e) {
		}
	}

	protected function generateFileParameter($id, $path) {
		return [
			'type' => 'file',
			'id'   => $id,
			'name' => basename($path),
			'path' => $path,
			'link' => $this->url->linkToRouteAbsolute(
				'files.viewcontroller.showFile', ['fileid' => $id]
			),
		];
	}

	private function generateCircleParameter(Share $share) {
		return [
			'type' => 'circle',
			'id'   => $share->getCircleId(),
			'name' => $share->getCircleName(),
			'link' => 'http://nextcloud/index.php/apps/circles/#' . $share->getCircleId()
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
