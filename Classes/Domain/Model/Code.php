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

/**
 * A code snippet
 *
 * @scope prototype
 * @entity
 */
class Code {

	/**
	 * @identity
	 * @var integer
	 */
	protected $uid;

	/**
	 * The code label
	 * @var string
	 * @validate StringLength(minimum = 3, maximum = 100)
	 */
	protected $label;

	/**
	 * The user the snippet belongs to
	 *
	 * @var Layh\Twitcode\Domain\Model\User
	 * @ManyToOne
	 */
	protected $user;

	/**
	 * The code itself
	 * @var string
	 * @validate StringLength(minimum = 10)
	 */
	protected $code;

	/**
	 * The code type the snippet belongs to
	 *
	 * @var Layh\Twitcode\Domain\Model\Codetype
	 * @ManyToOne
	 */
	protected $codetype;

	/**
	 * The code comment
	 * @var string
     * @validate StringLength(minimum = 5)
	 */
	protected $comment;

	/**
	 * Last modified
	 * @var \DateTime
	 */
	protected $modified;


	public function __construct() {
		$this->modified = new \DateTime();
	}

	/**
	 * @return string
	 */
	public function getLabel() {
		return $this->label;
	}

	/**
	 * @param string $label
	 * @return void
	 */
	public function setLabel($label) {
		$this->label = $label;
	}

	/**
	 * @param \Layh\Twitcode\Domain\Model\User $user
	 * @return void
	 */
	public function setUser(\Layh\Twitcode\Domain\Model\User $user) {
		$this->user = $user;
	}

	/**
	 * @return Layh\Twitcode\Domain\Model\User
	 */
	public function getUser() {
		return $this->user;
	}

	/**
	 * @param string $comment
	 * @return void
	 */
	public function setComment($comment) {
		$this->comment = $comment;
	}

	/**
	 * @return string
	 */
	public function getComment() {
		return $this->comment;
	}

	/**
	 * @param \Layh\Twitcode\Domain\Model\Codetype $codetype
	 * @return void
	 */
	public function setCodetype(\Layh\Twitcode\Domain\Model\Codetype $codetype) {
		$this->codetype = $codetype;
	}

	/**
	 * @return \Layh\Twitcode\Domain\Model\Codetype
	 */
	public function getCodetype() {
		return $this->codetype;
	}

	/**
	 * @param DateTime $modified
	 * @return void
	 */
	public function setModified(\DateTime $modified) {
		$this->modified = $modified;
	}

	/**
	 * @return \DateTime
	 */
	public function getModified() {
		return $this->modified;
	}

	/**
	 * @param string $code
	 * @return void
	 */
	public function setCode($code) {
		$this->code = $code;
	}

	/**
	 * @return string
	 */
	public function getCode() {
		return $this->code;
	}

	/**
	 * @param integer $uid
	 * @return void
	 */
	public function setUid($uid) {
		$this->uid = $uid;
	}

	/**
	 * @return integer
	 */
	public function getUid() {
		return $this->uid;
	}

}
?>
