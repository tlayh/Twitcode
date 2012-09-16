<?php
namespace Layh\Twitcode\Domain\Model;

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

use Doctrine\ORM\Mapping as ORM;
use \TYPO3\FLOW3\Annotations as FLOW3;

/**
 * A code type
 *
 * @FLOW3\Scope("prototype")
 * @FLOW3\Entity
 */
class Codetype {

	/**
	 * The name
	 *
	 * @var string
	 */
	protected $name = '';

	/**
	 * The type
	 *
	 * @var string
	 * @FLOW3\Identity
	 */
	protected $type = '';

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return void
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @param string $type
	 * @return void
	 */
	public function setType($type) {
		$this->type = $type;
	}

	/**
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

}
?>
