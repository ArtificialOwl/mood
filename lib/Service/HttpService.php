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

namespace OCA\Mood\Service;

use OCA\Mood\Exceptions\HttpRequestException;

class HttpService {


	public function __construct() {
	}


	public function getMetaFromWebsite($url) {

		try {
			$html = self::file_get_contents_curl($url);
			$tags = self::getMetaFromHtml($html);

			$meta = self::fillWithOpenGraph($tags);

			$meta['url'] = $url;
		} catch (\Exception $e) {
			throw $e;
		}

		return $meta;
	}


	public static function file_get_contents_curl($url, $bin = false) {
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);


		if ($bin === true) {
			curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
		}

		$data = curl_exec($ch);
		curl_close($ch);

		if ($data === false) {
			throw new HttpRequestException();
		}

		return $data;
	}


	public static function getMetaFromHtml($str) {
		$pattern = '~<\s*meta\s 
		(?=[^>]*?
    	\b(?:name|property|http-equiv)\s*=\s*
    	(?|"\s*([^"]*?)\s*"|\'\s*([^\']*?)\s*\'|
	    ([^"\'>]*?)(?=\s*/?\s*>|\s\w+\s*=))
  		)

  		[^>]*?\bcontent\s*=\s*
    	(?|"\s*([^"]*?)\s*"|\'\s*([^\']*?)\s*\'|
    	([^"\'>]*?)(?=\s*/?\s*>|\s\w+\s*=))
  		[^>]*>
  	~ix';

		if (preg_match_all($pattern, $str, $out)) {
			return array_combine($out[1], $out[2]);
		}

		return array();
	}


	public static function fillWithOpenGraph($tags) {
		return [
			'title'       => ((key_exists('og:title', $tags)) ? $tags['og:title'] : ''),
			'thumb'       => ((key_exists('og:image', $tags)) ? $tags['og:image'] : ''),
			'description' => ((key_exists('og:description', $tags)) ? $tags['og:description'] : ''),
			'website'     => ((key_exists('og:site_name', $tags)) ? $tags['og:site_name'] : ''),
		];
	}

}