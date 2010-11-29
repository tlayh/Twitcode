<?php
declare(ENCODING = 'utf-8');
namespace F3\Twitcode\ViewHelpers;

require_once('/var/www/vhosts/twitcode.org/subdomains/flow/httpdocs/Packages/Application/Twitcode/Resources/Private/Lib/geshi/geshi.php');

/**
 * A view helper to display the sintaxhighlight using GeSHi
 *
 * = Examples =
 *
 * <code title="Simple">
 * <tc:geshi code={code.code} />
 * </code>
 *
 * Output:
 * <img class="gravatar" src="http://www.gravatar.com/avatar/<hash>?d=http%3A%2F%2Fdomain.com%2Fgravatar_default.gif" />
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @scope prototype
 */

class GeSHiViewHelper extends \F3\Fluid\Core\ViewHelper\AbstractViewHelper {

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
