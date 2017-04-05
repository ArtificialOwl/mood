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

	onEventNewMood: function (mood, shares) {
		this.newMood(mood, shares);
	},


	onEventPastedMood: function (mood) {
		this.getDataFromUrl(mood);
	},


	newMood: function (mood, shares) {
		api.createMood(mood, shares, actions.newMoodResult);
	},


	getDataFromUrl: function (url) {
		api.getDataFromUrl(url, actions.getDataFromUrlResult);
	},


	getDataFromUrlResult: function (result) {
		if (result.status != 1) return;
		nav.fillWebsiteInfos(result.data);
	},


	newMoodResult: function (result) {
		console.log("_2_" + JSON.stringify(result));
	}

};


