<?php
namespace Layh\Twitcode\Domain\Repository;

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
 * A repository for comments
 */
class CommentRepository extends \TYPO3\FLOW3\Persistence\Repository {

	/**
	 * @var array
	 */
	protected $defaultOrderings = array('modified'=> \TYPO3\FLOW3\Persistence\QueryInterface::ORDER_ASCENDING);

	/**
	 * @var Layh\Twitcode\Domain\Repository\UserRepository
	 * @inject
	 */
	protected $userRepository;

	/**
	 * returns all comments by a single user
	 *
	 * @param int $user
	 * @return void
	 * @author Thomas Layh <develop@layh.com>
	 */
	public function findCommentsByUser($userId) {
		$user = $this->userRepository->findByUserId($userId);

		$query = $this->createQuery();
		$result = $query
				->matching($query->equals('user', $user))
				->setOrderings(array('modified' => \TYPO3\FLOW3\Persistence\QueryInterface::ORDER_DESCENDING))
				->execute();
		return $result->toArray();
	}

}
?>
