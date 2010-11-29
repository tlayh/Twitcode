<?php
declare(ENCODING = 'utf-8');
namespace F3\Twitcode\Domain\Repository;

/**
 * A repository for books
 */
class CodeRepository extends \F3\FLOW3\Persistence\Repository {

	/**
	 * @var \F3\Twitcode\Domain\Repository\UserRepository
	 * @inject
	 */
	protected $userRepository;

	public function findLatestSnippets($count) {
		$query = $this->createQuery();
		$result = $query
			->setLimit($count)
			->matching($query->logicalNot($query->equals('modified', '')))
			->setOrderings(array('modified' => \F3\FLOW3\Persistence\QueryInterface::ORDER_DESCENDING))
			->execute();
		return $result->toArray();
	}

	public function findNextHighestUidRecord() {
		$query = $this->createQuery();
		$result = $query
				->setLimit(1)
				->matching($query->logicalNot($query->equals('uid', '')))
				->setOrderings(array('uid' => \F3\FLOW3\Persistence\QueryInterface::ORDER_DESCENDING))
				->execute();
		return $result->getFirst();
	}

	public function findByUser($userId) {
		$user = $this->userRepository->findByUserId($userId);
		
		$query = $this->createQuery();
		$result = $query
				->matching($query->equals('user', $user))
				->execute();
		return $result->toArray();
	}

	/**
	 * @param \F3\Twitcode\Domain\Model\Codetype $ct
	 * @return int
	 */
	public function findCountByCodetype(\F3\Twitcode\Domain\Model\Codetype $ct) {
		$query = $this->createQuery();
		$result = $query->matching($query->equals('codetype', $ct))
				->execute()
				->count();
		return $result;
	}

	/**
	 * @param \F3\Twitcode\Domain\Model\Codetype $ct
	 * @return \F3\Twitcode\Domain\Model\CodeRepository
	 */
	public function findByCodetype(\F3\Twitcode\Domain\Model\Codetype $ct) {
		$query = $this->createQuery();
		$result = $query->matching($query->equals('codetype', $ct))
				->execute();
		return $result;
	}

}
?>
