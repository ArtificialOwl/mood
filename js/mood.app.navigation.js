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

/** global: nav */
/** global: elements */
/** global: curr */
/** global: api */
/** global: circles */


var nav = {


	initNavigation: function () {

	},


	initCircles: function () {
		circles.searchCircles('all', '', 1, nav.initCirclesResult);
	},


	initCirclesResult: function (result) {
		curr.circles = [];
		$.each(result.data, function (k, item) {
			curr.circles.push({
				id: item.id,
				name: item.name
			});
			console.log("> " + JSON.stringify(item));
		});

		nav.fillSharesList();
	},


	fillWebsiteInfos: function (infos) {
		curr.websiteInfos = infos;

		elements.websiteInfos.empty();
		if (infos.thumb !== '') {
			elements.websiteInfos.append(
				'<img class="thumb" src="' + api.localUrlOfExternalImage(infos.thumb) + '">');
		}

		var website = (infos.title === '') ? infos.website : ' (' + infos.website + ') ';
		elements.websiteInfos.append('<b>' + infos.title + ' ' + website + '</b>');
		elements.websiteInfos.append('<br /> ' + infos.description);
		elements.websiteInfos.fadeIn(400);
	},


	fillSharesList: function () {

		elements.moodSharesList.empty();
		$.each(curr.circles, function (k, circle) {
			elements.moodSharesList.append(
				'<div class="sharesItem" data-id="circle:' + circle.id + '">' +
				'<table><tr><td><input type="checkbox" class="check" />' +
				'</td><td>' + circle.name + '</td></tr></table></div>');
		});

		elements.initExperienceMoodSharesItems();
	},


	switchSharesDisplay: function () {
		var sharePosition = elements.moodShares.position();

		elements.moodSharesList.css({
			position: 'absolute',
			top: sharePosition.top + 50,
			left: sharePosition.left + 10
		});

		if (curr.sharesDisplayed) {
			curr.sharesDisplayed = false;
			elements.moodSharesList.fadeOut(400);
		} else {
			curr.sharesDisplayed = true;
			elements.moodSharesList.fadeIn(400);
		}
	}


};

