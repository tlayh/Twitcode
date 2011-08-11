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
 * A discussion
 *
 * @scope prototype
 * @entity
 */
class Discussion {

	/**
	 * The user the discussion belongs to
	 * @var Layh\Twitcode\Domain\Model\User
	 * @ManyToOne
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
	 * @var Layh\Twitcode\Domain\Model\Code
	 * @ManyToOne
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
	 * @param \DateTime $modified
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
	 * @param \Layh\Twitcode\Domain\Model\Code $code
	 * @return void
	 */
	public function setCode($code) {
		$this->code = $code;
	}

	/**
	 * @return \Layh\Twitcode\Domain\Model\Code
	 */
	public function getCode() {
		return $this->code;
	}

}
?>
