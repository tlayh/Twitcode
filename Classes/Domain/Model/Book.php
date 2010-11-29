<?php
declare(ENCODING = 'utf-8');
namespace F3\Twitcode\Domain\Model;

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
 * A book
 *
 * @scope prototype
 * @entity
 */
class Book {

	/**
	 * The book name
	 *
	 * @var string
	 */
	protected $name = '';

	/**
	 * The book description
	 *
	 * @var string
	 */
	protected $description = '';

	/**
	 * The codetype the book is assigned to
	 *
	 * @var \F3\Twitcode\Domain\Model\Book
	 */
	protected $codetype;

	/**
	 * The amazon link for the book
	 *
	 * @var string
	 */
	protected $amazon;

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function setDescription($description) {
		$this->description = $description;
	}

	public function getDescription() {
		return $this->description;
	}

	public function setCodetype($codetype) {
		$this->codetype = $codetype;
	}

	public function getCodetype() {
		return $this->codetype;
	}

	public function setAmazon(string $amazon) {
		$this->amazon = $amazon;
	}

	public function getAmazon() {
		return $this->amazon;
	}

}
?>
