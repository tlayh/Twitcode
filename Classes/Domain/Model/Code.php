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
 * A code snippet
 *
 * @FLOW3\Scope("prototype")
 * @FLOW3\Entity
 */
class Code {

	/**
	 * @var integer
	 * @FLOW3\Identity
	 */
	protected $uid;

	/**
	 * @var string
	 * @FLOW3\Validate(type="Text")
	 * @FLOW3\Validate(type="StringLength", options={"minimum"=5, "maximum"=100})
	 */
	protected $label;

	/**
	 * @var \Layh\Twitcode\Domain\Model\User
	 * @ORM\ManyToOne
	 */
	protected $user;

	/**
	 * @var string
	 * @FLOW3\Validate(type="Text")
	 * @FLOW3\Validate(type="StringLength", options={"minimum"=10})
	 * @ORM\Column(type="text")
	 */
	protected $code;

	/**
	 * The code type the snippet belongs to
	 *
	 * @var \Layh\Twitcode\Domain\Model\Codetype
	 * @ORM\ManyToOne
	 */
	protected $codetype;

	/**
	 * The code description
	 * @var string
	 * @FLOW3\Validate(type="StringLength", options={"minimum"=10})
	 * @ORM\Column(type="text")
	 */
	protected $description;

	/**
	 * The short URL that is returned from bit.ly
	 * it is saved here to minimize the requests to bit.ly for generating short urls
	 * @var string
	 */
	protected $shortUrl;

	/**
	 * @var \Doctrine\Common\Collections\Collection<\Layh\Twitcode\Domain\Model\Tag>
	 * @ORM\ManyToMany(inversedBy="codes", cascade={"all"})
	 */
	protected $tags;

	/**
	 * Last modified
	 * @var \DateTime
	 */
	protected $modified;


	public function __construct() {
		$this->modified = new \DateTime();
		$this->tags = new \Doctrine\Common\Collections\ArrayCollection();
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
	 * @return \Layh\Twitcode\Domain\Model\User
	 */
	public function getUser() {
		return $this->user;
	}

	/**
	 * @param string $description
	 * @return void
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
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
	 * @param \DateTime|\Layh\Twitcode\Domain\Model\DateTime $modified
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

	/**
	 * setter for tags
	 *
	 * @param \Doctrine\Common\Collections\ArrayCollection <\Layh\Twitcode\Domain\Model\Tag> $tags
	 * @return void
	 */
	public function setTags(\Doctrine\Common\Collections\ArrayCollection $tags) {
		$this->tags = clone $tags;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection<\Layh\Twitcode\Domain\Model\Tag>
	 */
	public function getTags() {
		return clone $this->tags;
	}

	/**
	 * Add a tag to this code snippet
	 *
	 * @param \Layh\Twitcode\Domain\Model\Tag $tag
	 * @return void
	 */
	public function addTag(\Layh\Twitcode\Domain\Model\Tag $tag) {
		$this->tags->add($tag);
	}

	/**
	 * returns a comma seperated tag list
	 *
	 * @return string
	 */
	public function getTagList() {
		$tagList = '';

		/** $tag \Layh\Twitcode\Domain\Model\Tag */
		foreach($this->tags as $tag) {
			$tagList .= $tag->getTitle();
			$tagList .= ' ,';
		}

		// remove last comma
		$tagList = \substr($tagList, 0, strlen($tagList)-1);

		return $tagList;
	}

	public function removeAllTags() {
		$this->tags = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * @param string $shortUrl
	 */
	public function setShortUrl($shortUrl) {
		$this->shortUrl = $shortUrl;
	}

	/**
	 * @return string
	 */
	public function getShortUrl() {
		return $this->shortUrl;
	}

}
?>
