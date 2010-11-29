<?php
declare(ENCODING = 'utf-8');
namespace F3\Twitcode\Domain\Model;

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
}
?>
