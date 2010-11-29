<?php
declare(ENCODING = 'utf-8');
namespace F3\Twitcode\Domain\Model;

/**
 * A code type
 *
 * @scope prototype
 * @entity
 */
class Codetype {

	/**
	 * The name
	 *
	 * @var string
	 */
	protected $name = '';

	/**
	 * The type
	 *
	 * @var string
	 * @identity
	 */
	protected $type = '';

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
	 * @param string $type
	 * @return void
	 */
	public function setType($type) {
		$this->type = $type;
	}

	/**
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

}
?>
