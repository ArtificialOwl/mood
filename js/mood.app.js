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
/** global: elements */

var api = OCA.Mood.api;
var curr = {
	mood: '',
};


$(document).ready(function () {

	/**
	 * @constructs Navigation
	 */
	var Navigation = function () {

		$.extend(Navigation.prototype, curr);
		$.extend(Navigation.prototype, nav);
		$.extend(Navigation.prototype, elements);
		$.extend(Navigation.prototype, actions);

		this.init();
	};


	Navigation.prototype = {

		init: function () {
			elements.initElements();
			elements.initUI();
			elements.initExperienceMoodPost();
			nav.initNavigation();
		}
	};


	/**
	 * @constructs Notification
	 */
	var Notification = function () {
		this.initialize();
	};

	Notification.prototype = {

		initialize: function () {

			//noinspection SpellCheckingInspection
			var notyf = new Notyf({
				delay: 5000
			});

			this.onSuccess = function (text) {
				notyf.confirm(text);
			};

			this.onFail = function (text) {
				notyf.alert(text);
			};

		}

	};

	OCA.Mood.Navigation = Navigation;
	OCA.Mood.navigation = new Navigation();

	OCA.Notification = Notification;
	OCA.notification = new Notification();

});

