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
 * A discussion
 *
 * @scope prototype
 * @entity
 */
class Discussion {

	/**
	 * @var integer
	 * @Id
	 * @GeneratedValue
	 */
	protected $id = 0;

	/**
	 * The user the discussion belongs to
	 * @var F3\Twitcode\Domain\Model\User
	 */
	protected $user;

	/**
	 * The discussion
	 * @var string
	 * @validate StringLength(minimum = 10)
	 */
	protected $discussion;

	/**
	 * The code the discussion belongs to
	 * 
	 * @var F3\Twitcode\Domain\Model\Code
	 */
	protected $code;

	/**
	 * Last modified
	 * @var \DateTime
	 */
	protected $modified;

	public function __construct() {
		$this->modified = new \DateTime();
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function getId() {
		return $this->id;
	}

	/**
	 * @param \F3\Twitcode\Domain\Model\User $user
	 * @return void
	 */
	public function setUser(\F3\Twitcode\Domain\Model\User $user) {
		$this->user = $user;
	}

	/**
	 * @return F3\Twitcode\Domain\Model\User
	 */
	public function getUser() {
		return $this->user;
	}

	/**
	 * @param string $discussion
	 * @return void
	 */
	public function setDiscussion($discussion) {
		$this->discussion = $discussion;
	}

	/**
	 * @return string
	 */
	public function getDiscussion() {
		return $this->discussion;
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
	 * @param \F3\Twitcode\Domain\Model\Code $code
	 * @return void
	 */
	public function setCode($code) {
		$this->code = $code;
	}

	/**
	 * @return \F3\Twitcode\Domain\Model\Code
	 */
	public function getCode() {
		return $this->code;
	}

}
?>
