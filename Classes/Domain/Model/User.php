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
 * A book
 *
 * @scope prototype
 * @entity
 * @lazy
 */
class User {

	/**
	 * The screen name
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * The userid
	 *
	 * @var integer
	 */
	protected $user_id;

	/**
	 * @var \Layh\Twitcode\Domain\Model\Settings
	 * @OneToOne
	 */
	protected $settings;

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
	 * @param \Layh\Twitcode\Domain\Model\Layh\Twitcode\Domain\Model\Settings $settings
	 */
	public function setSettings($settings) {
		$this->settings = $settings;
	}

	/**
	 * @return \Layh\Twitcode\Domain\Model\Layh\Twitcode\Domain\Model\Settings
	 */
	public function getSettings() {
		return $this->settings;
	}
}
?>
