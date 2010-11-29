<?php
declare(ENCODING = 'utf-8');
namespace F3\Twitcode\Domain\Model;

/**
 * A code snippet
 *
 * @scope prototype
 * @entity
 */
class Code {

	/**
	 * @var integer
	 * @identity
	 */
	protected $uid = 0;

	/**
	 * The code label
	 * @var string
	 * @validate StringLength(minimum = 3, maximum = 100)
	 */
	protected $label;

	/**
	 * The user the snippet belongs to
	 * @var F3\Twitcode\Domain\Model\User
	 */
	protected $user;

	/**
	 * The code itself
	 * @var string
	 * @validate StringLength(minimum = 10)
	 */
	protected $code;

	/**
	 * The code type the snippet belongs to
	 * 
	 * @var F3\Twitcode\Domain\Model\Codetype
	 */
	protected $codetype;

	/**
	 * The code comment
	 * @var string
     * @validate StringLength(minimum = 5)
	 */
	protected $comment;

	/**
	 * Last modified
	 * @var \DateTime
	 */
	protected $modified;

	public function __construct() {
		$this->modified = new \DateTime();
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
	 * @param \F3\Twitcode\Domain\Model\User $user
	 * @return void
	 */
	public function setUser(\F3\Twitcode\Domain\Model\User $user) {
		$this->user = $user;
	}

	/**
	 * @return F3\Twitcode\Domain\Model\User
	 */
	public function getUser() {
		return $this->user;
	}

	/**
	 * @param string $comment
	 * @return void
	 */
	public function setComment($comment) {
		$this->comment = $comment;
	}

	/**
	 * @return string
	 */
	public function getComment() {
		return $this->comment;
	}

	/**
	 * @param \F3\Twitcode\Domain\Model\Codetype $codetype
	 * @return void
	 */
	public function setCodetype(\F3\Twitcode\Domain\Model\Codetype $codetype) {
		$this->codetype = $codetype;
	}

	/**
	 * @return \F3\Twitcode\Domain\Model\Codetype
	 */
	public function getCodetype() {
		return $this->codetype;
	}

	/**
	 * @param DateTime $modified
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

}
?>
