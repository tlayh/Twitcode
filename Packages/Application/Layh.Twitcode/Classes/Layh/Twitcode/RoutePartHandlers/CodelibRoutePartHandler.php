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

use \TYPO3\Flow\Annotations as Flow;

/**
 * Codelib route part handler
 *
 * @scope prototype
 */
class CodelibRoutePartHandler extends \TYPO3\Flow\Mvc\Routing\DynamicRoutePart {

	/**
	 * Checks for the current code type
	 *
	 * @param string $value The value (ie. part of the request path) to match. This string is rendered by findValueToMatch()
	 * @return boolean TRUE if the request path formally matched
	 */
	protected function matchValue($value) {
		if (!parent::matchValue($value)) {
			return FALSE;
		}
		$this->value = array(
			'__identity' => array(
				'type' => $value
			)
		);
		return TRUE;
	}

	/**
	 *
	 *
	 * @param string $requestPath The request path acting as the subject for matching in this Route Part
	 * @return string The post identifying part of the request path or an empty string if it doesn't match
	 */
	protected function findValueToMatch($requestPath) {
		return $requestPath;
	}

	/**
	 * Resolves the name of the codetype
	 *
	 * @param \Layh\Twitcode\Domain\Model\Codetype $value The Codetype object
	 * @return boolean TRUE if the codetype could be resolved and stored in $this->value, otherwise FALSE.
	 */
	protected function resolveValue($value) {
		if (!$value instanceof \Layh\Twitcode\Domain\Model\Codetype) { return FALSE; }
		$this->value = $value->getType();

		return TRUE;
	}

}
