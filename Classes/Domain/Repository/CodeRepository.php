<?php
declare(ENCODING = 'utf-8');
namespace F3\Twitcode\Domain\Repository;

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
