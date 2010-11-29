<?php
declare(ENCODING = 'utf-8');
namespace F3\Twitcode\Domain\Model;

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
