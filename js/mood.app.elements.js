/*
 * Circles - Bring cloud-users closer together.
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
 */

/** global: OC */
/** global: OCA */
/** global: Notyf */

/** global: nav */
/** global: actions */
/** global: curr */
/** global: api */


var elements = {

	moodText: null,
	websiteInfos: null,

	initElements: function () {
		elements.ActivityHeader = null;

		elements.moody = $('#moody');
		elements.moodText = $('#mood');
		elements.moodShares = $('#mood_shares');
		elements.moodSharesList = $('#shares_list');
		elements.moodSubmit = $('#mood_submit');
		elements.websiteInfos = $('#website_infos');
	},


	initUI: function () {
		elements.websiteInfos.hide(0);
		elements.moodSharesList.hide(0);

		var theme = $('#body-user').find('#header').css('background-color');
		elements.moody.css('background-color', theme);
		elements.websiteInfos.css('border-color', theme);
	},


	initExperienceMood: function () {
		elements.initExperienceMoodPost();
		elements.initExperienceMoodPaste();
		elements.initExperienceMoodShares();
		elements.initExperienceMoodSharesItems();
	},


	initExperienceMoodPost: function () {

		elements.moodText.on('keypress', function (e) {
			if (e.keyCode === 13) {
				actions.onEventPostMood($(this).val());
			}
		});

		elements.moodSubmit.on('click', function () {
			actions.onEventPostMood(elements.moodText.val());
		});
	},

	initExperienceMoodPaste: function () {
		elements.moodText.on('paste', function (e) {
			var pastedData = e.originalEvent.clipboardData.getData('text');
			actions.onEventPastedMood(pastedData);
		});
	},

	initExperienceMoodShares: function () {

		elements.moodShares.on('click', function (e) {
			nav.switchSharesDisplay();
			e.stopPropagation();
		});

		elements.moodSharesList.on('click', function (e) {
			e.stopPropagation();
		});

		$(window).click(function () {
			if (curr.sharesDisplayed) {
				nav.switchSharesDisplay();
			}
		});
	},


	initExperienceMoodSharesItems: function () {

		$('.sharesItem').on('click', function () {
			curr.switchShare($(this).attr('data-id'));
			elements.refreshShares();
		});

	},


	refreshShares: function () {
		elements.moodSharesList.children('div').each(function () {
			if (curr.isShared($(this).attr('data-id'))) {
				$(this).find('input').prop('checked', true);
			}
			else {
				$(this).find('input').prop('checked', false);
			}
		});

	},


	integrateMoodToActivity: function () {
		elements.ActivityHeader = $('#app-content');

		var moodHtml = '';
		moodHtml += '<div id="moody">';
		moodHtml += '<div class="lightenbg"></div>';
		moodHtml += '<input class="mood_input" id="mood" type="text" placeholder="' +
			t('mood', 'New mood') + '">';
		moodHtml +=
			'<input type="submit" class="mood_input" id="mood_shares" value="' +
			t('mood', 'Share with ...') + '" />';
		moodHtml +=
			'<input class="mood_input" id="mood_submit" type="submit" value="' +
			t('mood', 'Share your mood') + '"/>';
		moodHtml += '</div>';
		moodHtml += '<div id="website_infos"></div><div id="shares_list"></div>';

		elements.ActivityHeader.prepend(moodHtml);
	}


};