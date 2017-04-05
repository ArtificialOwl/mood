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

(function () {


	/**
	 * @constructs Circles
	 */
	var Mood = function () {
		this.initialize();
	};

	Mood.prototype = {


		initialize: function () {

			var self = this;

			this.createMood = function (data, shares, callback) {
				var result = {status: -1};
				$.ajax({
					method: 'PUT',
					url: OC.generateUrl(OC.linkTo('mood', 'mood')),
					data: {
						data: data,
						shares: shares
					}
				}).done(function (res) {
					self.onCallback(callback, res);
				}).fail(function () {
					self.onCallback(callback, result);
				});
			};


			this.getDataFromUrl = function (url, callback) {
				var result = {status: -1};
				$.ajax({
					method: 'GET',
					url: OC.generateUrl(OC.linkTo('mood', 'data/url')),
					data: {
						url: url
					}
				}).done(function (res) {
					self.onCallback(callback, res);
				}).fail(function () {
					self.onCallback(callback, result);
				});
			};


			this.localUrlOfExternalImage = function (url) {
				return OC.generateUrl(OC.linkTo('mood', 'data/image') + '?url=' +
					encodeURIComponent(url));
			};


			this.onCallback = function (callback, result) {
				if (callback && (typeof callback === "function")) {
					callback(result);
				}
			};


		}
	};

	OCA.Mood = Mood;
	OCA.Mood.api = new Mood();

})();

