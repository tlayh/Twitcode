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
 * Tag model for the Layh.Twitcode package
 *
 * @scope prototype
 * @valueobject
 *
 * @author Thomas Layh <develop@layh.com>
*/
class Tag {

	/**
	 * @var string
	 * @validate Alphanumeric, StringLength(minimum = 1, maximum = 20)
     * @identitiy
	 */
	protected $title;

	/**
	 * The posts tagged with this tag
	 *
	 * @var \Doctrine\Common\Collections\ArrayCollection<\Layh\Twitcode\Domain\Model\Code>
	 * @ManyToMany(mappedBy="tags")
	 */
	protected $codes;

	/**
	 * Constructs this tag
	 *
	 * @param string $name
	 */
	public function __construct($title) {
		$this->title = strtoupper($title);
		$this->codes = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return $this->title;
	}
}
