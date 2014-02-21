<?php
namespace Layh\Twitcode\Domain\Model;

/*                                                                        *
 * This script belongs to the Flow package "Layh.Twitcode".              *
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

use Doctrine\ORM\Mapping as ORM;
use \TYPO3\Flow\Annotations as Flow;

/**
 * Tag model for the Layh.Twitcode package
 *
 * @Flow\Scope("prototype")
 * @Flow\ValueObject
 *
 * @author Thomas Layh <develop@layh.com>
*/
class Tag {

	/**
	 * @var string
	 * @Flow\Validate(type="Alphanumeric")
	 * @Flow\Validate(type="StringLength", options={"minimum"= 1, "maximum"= 20})
	 */
	protected $title;

	/**
	 * The posts tagged with this tag
	 *
	 * @var \Doctrine\Common\Collections\ArrayCollection<\Layh\Twitcode\Domain\Model\Code>
	 * @ORM\ManyToMany(mappedBy="tags")
	 */
	protected $codes;

	/**
	 * Constructs this tag
	 *
	 * @param string $title
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
