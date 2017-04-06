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

	newMood: null,
	websiteInfos: null,

	initElements: function () {
		elements.ActivityHeader = null;

		elements.moody = $('#moody');
		elements.newMood = $('#mood');
		elements.submitMood = $('#mood_submit');
		elements.websiteInfos = $('#website_infos');
	},


	initUI: function () {
		elements.websiteInfos.hide(0);

		var theme = $('#body-user').find('#header').css('background-color');
		elements.moody.css('background-color', theme);
		elements.websiteInfos.css('border-color', theme);
	},


	initExperienceMoodPost: function () {
		elements.newMood.on('keypress', function (e) {
			if (e.keyCode === 13) {
				actions.onEventPostMood($(this).val());
			}
		});

		elements.submitMood.on('click', function (e) {
			actions.onEventPostMood(elements.newMood.val());
			return true;
		});

		elements.newMood.on('paste', function (e) {
			var pastedData = e.originalEvent.clipboardData.getData('text');
			actions.onEventPastedMood(pastedData);
		});
	},


	integrateMoodToActivity: function () {
		console.log("[debug] integrating Mood into Activity");

		elements.ActivityHeader = $('#app-content');

		var moodHtml = '';
		moodHtml += '<div id="moody">';
		moodHtml += '<div class="lightenbg"></div>';
		moodHtml += ' <input class="mood_input" id="mood" type="text" placeholder="' +
			t('mood', 'New mood') + '">';
		moodHtml +=
			' <input class="mood_input" id="mood_submit" type="submit" value="Share your mood"/>';
		moodHtml += '</div>';
		moodHtml += '<div id="website_infos"></div>';

		elements.ActivityHeader.prepend(moodHtml);
	}


};