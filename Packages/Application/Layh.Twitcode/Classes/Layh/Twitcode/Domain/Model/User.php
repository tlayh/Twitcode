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
use \TYPO3\Flow\Annotations as Flow;

/**
 * A twitter user
 *
 * @Flow\Entity
 */
class User {

	/**
	 * The screen name
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * The twitter userid
	 *
	 * @var integer
	 */
	protected $user_id;

	/**
	 * Notifications enabled or disabeld
	 *
	 * @var bool
	 */
	protected $notification = FALSE;

	/**
	 * Flattr id if any is set
	 *
	 * @var string
	 */
	protected $flattrId = '';

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
	 * @param integer $user_id
	 * @return void
	 */
	public function setUserId($user_id) {
		$this->user_id = $user_id;
	}

	/**
	 * @return integer
	 */
	public function getUserId() {
		return $this->user_id;
	}

	/**
	 * @param bool $notification
	 */
	public function setNotification($notification) {
		$this->notification = $notification;
	}

	/**
	 * @return bool
	 */
	public function getNotification() {
		return $this->notification;
	}

	/**
	 * @param string $flattrId
	 */
	public function setFlattrId($flattrId) {
		$this->flattrId = $flattrId;
	}

	/**
	 * @return string
	 */
	public function getFlattrId() {
		return $this->flattrId;
	}

}
