<?php
namespace Layh\Twitcode\Domain\Model;

/*                                                                        *
 * This script belongs to the FLOW3 package "Layh.Twitcode".              *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        */

/**
 * Settings model for the Layh.Twitcode package
 *
 * @author Thomas Layh <develop@layh.com>
 * @scope prototype
 * @entity
*/
class Settings {

	/**
	 * The user the settings belong to
	 *
	 * @var \Layh\Twitcode\Domain\Model\User
	 * @ManyToOne
	 */
	protected $user;

	/**
	 * @var boolean
	 */
	protected $commentNotification;

	/**
	 * @param \Layh\Twitcode\Domain\Model\User $user
	 */
	public function setUser($user) {
		$this->user = $user;
	}

	/**
	 * @return \Layh\Twitcode\Domain\Model\User
	 */
	public function getUser() {
		return $this->user;
	}

	/**
	 * @param boolean $commentNotification
	 */
	public function setCommentNotification($commentNotification) {
		$this->commentNotification = $commentNotification;
	}

	/**
	 * @return boolean
	 */
	public function isCommentNotificationActive() {
		return $this->commentNotification;
	}
}
