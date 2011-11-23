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

require_once('/var/www/vhosts/twitcode.org/httpdocs/Packages/Applications/Layh.Twitcode/Resources/Private/Lib/geshi/geshi.php');

/**
 * A view helper to display the sintaxhighlight using GeSHi
 *
 * = Examples =
 *
 * <code title="Simple">
 * <tc:geshi code="{code.code}" codetype="{code.codetype.type}" />
 * </code>
 *
 * @scope prototype
 */

class GeSHiViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Initialize arguments
	 *
	 * @return void
	 */
	public function initializeArguments() {
		parent::initializeArguments();
		$this->registerArgument('code', 'string', 'Snippet', TRUE);
		$this->registerArgument('codetype', 'string', 'codetype to render');
	}

	/**
	 * Render the syntax highlight
	 *
	 * @return string rendered sourcecode
	 * @author Thomas Layh <develop@layh.com>
	 */
	public function render() {

		$code= $this->arguments['code'];
		$codetype = $this->arguments['codetype'];

		// parameters: code, codetype
		$geshi = new \GeSHi($code, $codetype);
		$geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS);
		$geshi->set_header_type(GESHI_HEADER_DIV);
		$geshi->set_overall_class('code');
		return $geshi->parse_code();
	}

}
