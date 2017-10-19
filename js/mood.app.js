/*
 * Mood
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

/** global: nav */
/** global: actions */
/** global: elements */

const circles_api = OCA.Circles.api;
const api = OCA.Mood.api;
let curr = {
	mood: '',
	circles: [],
	shares: [],
	sharesDisplayed: false,
	requestingInfos: false,
	websiteInfos: {},

	isShared: function (share) {
		return ($.inArray(share, curr.shares) > -1);
	},

	addShare: function (share) {
		if (!curr.isShared(share)) {
			curr.shares.push(share);
		}
	},

	remShare: function (share) {
		const e = curr.shares.indexOf(share);
		if (e > -1) {
			curr.shares.splice(e, 1);
		}
	},

	switchShare: function (share) {
		if (curr.isShared(share)) {
			curr.remShare(share);
		} else {
			curr.addShare(share);
		}
	}
};

const Navigation = function () {

	$.extend(Navigation.prototype, curr);
	$.extend(Navigation.prototype, nav);
	$.extend(Navigation.prototype, elements);
	$.extend(Navigation.prototype, actions);

	this.init();
};

Navigation.prototype = {

	init: function () {
		elements.integrateMoodToActivity();
		elements.initElements();
		elements.initUI();
		elements.initExperienceMood();

		nav.initNavigation();
		nav.initCircles();
	}
};


OCA.Mood.Navigation = Navigation;

$(document).ready(function () {
	OCA.Mood.navigation = new Navigation();
});

