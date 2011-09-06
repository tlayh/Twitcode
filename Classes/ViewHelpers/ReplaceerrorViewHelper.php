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
 * A view helper to display nicer error messages
 *
 * = Examples =
 *
 * <code title="Simple">
 * <tc:geshi code={code.code} />
 * </code>
 *
 * @scope prototype
 */

class ReplaceerrorViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Initialize arguments
	 *
	 * @return void
	 */
	public function initializeArguments() {
		parent::initializeArguments();
		$this->registerArgument('error', 'string', 'Error');
	}

	/**
	 * Render the syntax highlight
	 */
	public function render() {
		$error = $this->arguments['error'];

		switch($error) {
			case 'code.label': $error = 'Label'; break;
			case 'code.description': $error = 'Comment'; break;
			case 'code.code': $error = 'Code'; break;
		}

		return $error;
	}

}
