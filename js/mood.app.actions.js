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
/** global: elements */
/** global: curr */
/** global: api */


var actions = {

	onEventPostMood: function (text) {
		var mood = {
			text: text,
			website: curr.websiteInfos
		};

		elements.websiteInfos.hide(300);
		elements.moodText.val('');
		curr.requestingInfos = false;

		this.newMood(mood, curr.shares);
	},


	onEventPastedMood: function (mood) {
		this.getDataFromUrl(mood);
	},


	newMood: function (mood, shares) {
		$.each(shares, function (k, share) {
			var info = share.split(':', 2);
			if (info[0] === 'circle') {
				circles.shareToCircle(info[1], 'mood', '', mood, actions.newMoodResult);
			}
		});
	},


	newMoodResult: function (result) {
		console.log("result new mood ; " + JSON.stringify(result));
	},


	getDataFromUrl: function (url) {
		curr.requestingInfos = true;
		api.getDataFromUrl(url, actions.getDataFromUrlResult);
	},


	getDataFromUrlResult: function (result) {
		if (result.status !== 1) return;
		if (curr.requestingInfos === false) return;
		nav.fillWebsiteInfos(result.data);
	}

};


