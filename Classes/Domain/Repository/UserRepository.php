<?php
declare(ENCODING = 'utf-8');
namespace F3\Twitcode\Domain\Repository;

/**
 * A repository for books
 */
class UserRepository extends \F3\FLOW3\Persistence\Repository {

	public function findByUserId($userId) {
		$query = $this->createQuery();
		$result = $query->matching($query->equals('user_id', $userId))->setLimit(1)->execute();
		return $result->getFirst();
	}

}
?>
