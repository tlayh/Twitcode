<?php
namespace Layh\Twitcode\Tests\Functional\ViewHelper;
/**
 * ReplaceerrorViewHelperTest
 */

/**
 *
 */
class ReplaceerrorViewHelperTest extends \TYPO3\FLOW3\Tests\FunctionalTestCase {


	/**
	 * @test
	 */
	public function testErrorMessageReplaced() {
		$error = 'code.label';

		$viewHelper = $this->getAccessibleMock('Layh\Twitcode\ViewHelpers\ReplaceerrorViewHelper', array('render'));
		$viewHelper->expects($this->once())->method('render')->will($this->returnValue('code.label'));

		$replaceErrorViewHelper = new \Layh\Twitcode\ViewHelpers\ReplaceerrorViewHelper();
		$result = $viewHelper->render();

		$this->assertEquals('Label', $result);

	}

}