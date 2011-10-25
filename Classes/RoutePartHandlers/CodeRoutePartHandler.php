<?php
namespace Layh\Twitcode\RoutePartHandlers;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010 Thomas Layh <info@twitcode.org>
 *  All rights reserved
 *
 *  This script is part of the Twitcode project. The Twitcode project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Code route part handler
 *
 * @scope prototype
 */
class CodeRoutePartHandler extends \TYPO3\FLOW3\MVC\Web\Routing\DynamicRoutePart {

	/**
	 * Splits the given value into the date and title of the post and sets this
	 * value to an identity array accordingly.
	 *
	 * @param string $value The value (ie. part of the request path) to match. This string is rendered by findValueToMatch()
	 * @return boolean TRUE if the request path formally matched
	 */
	protected function matchValue($value) {
		if (!parent::matchValue($value)) {
			return FALSE;
		}

		$value = substr($value, 0, strpos($value, '/'));

		$this->value = array(
			'__identity' => array(
				'uid' => $value
			)
		);
		return TRUE;
	}

	/**
	 * Checks if the remaining request path starts with the path signature of a post, which
	 * is: YYYY/MM/DD/TITLE eg. 2009/03/09/my-first-blog-entry
	 *
	 * If the request path matches this pattern, the matching part is returned as the "value
	 * to match" for further processing in matchValue(). The remaining part of the requestPath
	 * (eg. the format ".html") is ignored.
	 *
	 * @param string $requestPath The request path acting as the subject for matching in this Route Part
	 * @return string The post identifying part of the request path or an empty string if it doesn't match
	 */
	protected function findValueToMatch($requestPath) {
		return $requestPath;
	}

	/**
	 * Resolves the name of the code
	 *
	 * @param \Layh\Twitcode\Domain\Model\Code $value The Code object
	 * @return boolean TRUE if the code could be resolved and stored in $this->value, otherwise FALSE.
	 */
	protected function resolveValue($value) {
		if (!$value instanceof \Layh\Twitcode\Domain\Model\Code) return FALSE;
		$this->value = $value->getUid();

		// prepare label for url
		$label = strtolower(str_replace(' ', '-', $value->getLabel()));
		$label = preg_replace('/[^0-9a-zA-Z-_]/', '', $label);
		$this->value .= '/'.$label;


		return TRUE;
	}

}
