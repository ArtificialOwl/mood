<?php

/**
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
 *
 */

script('mood', 'mood');

script('mood', 'vendor/notyf');
style('mood', 'notyf');

script('mood', 'mood.app.elements');
script('mood', 'mood.app.actions');
script('mood', 'mood.app.navigation');
script('mood', 'mood.app');

style('mood', 'navigate');
?>


<div id="app-navigation">
	<div class="navigation-element">
		<input class="input" id="mood" type="text" placeholder="<?php p($l->t('New mood')); ?>"/>
		<input id="mood_submit" type="submit" value="Share your mood"/>
	</div>
	<div id="website_infos"></div>

</div>

<div id="emptycontent">
	<div class="icon-mood"></div>
	<h2><?php p($l->t('No social cloud on the horizon')); ?></h2>
</div>

<div id="app-content">
</div>