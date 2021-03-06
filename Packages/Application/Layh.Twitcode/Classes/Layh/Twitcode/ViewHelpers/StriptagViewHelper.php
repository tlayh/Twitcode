<?php
namespace Layh\Twitcode\ViewHelpers;

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
 * A view helper to remove script tags
 *
 * = Examples =
 *
 * <code title="Simple">
 * <tc:striptag></tc:striptag>
 * </code>
 *
 * @scope prototype
 */

class StriptagViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {


	/**
	 * Striptag Viewhelper
	 *
	 * @param string $value
	 * @return string
	 */
	public function render($value = NULL) {
		if ($value === NULL) {
			$value = $this->renderChildren();
		}

		if (!is_string($value)) {
			return $value;
		}

		$value = str_replace('<', '&lt;', $value);
		$value = str_replace('>', '&gt;', $value);

		return $value;
	}

}
