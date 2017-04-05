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
		elements.newMood = $('#mood');
		elements.websiteInfos = $('#website_infos');
	},


	initUI: function () {

		elements.websiteInfos.hide(0);

		$('.icon-mood').css('background-image',
			'url(' + OC.imagePath('mood', 'colored') + ')');
	},


	initExperienceMoodPost: function () {
		elements.newMood.on('keypress', function (e) {
			if (e.keyCode == 13) {
				actions.onEventNewMood($(this).val());
			}
			curr.mood = '';
		});

		elements.newMood.on('paste', function (e) {
			var pastedData = e.originalEvent.clipboardData.getData('text');
			actions.onEventPastedMood(pastedData);

		});
	},


}
