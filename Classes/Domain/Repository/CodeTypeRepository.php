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
class CodetypeRepository extends \F3\FLOW3\Persistence\Repository {

	/**
	 * @var F3\Twitcode\Domain\Repository\CodeRepository
	 * @inject
	 */
	protected $codeRepository;

    /**
	* overriding findAll method to get the oder of the codetypes
	* @return Array of Codetypes
	*/
    public function findAll() {
        $query = $this->createQuery();
        $result = $query
                ->matching($query->logicalNot($query->equals('name', '')))
				->setOrderings(array('name' => \F3\FLOW3\Persistence\QueryInterface::ORDER_ASCENDING))
                ->execute();
        return $result->toArray();
    }	

	/**
	* get all codetypes that also have snippets available
	* also return the count of snippets each codetype has
	*
	* @return array
	*/
	public function findAllWithSnippets() {
		$query = $this->createQuery();
		$codeTypes = $query
				->matching($query->logicalNot($query->equals('name', '')))
				->setOrderings(array('name' => \F3\FLOW3\Persistence\QueryInterface::ORDER_ASCENDING))
				->execute();

		$codetypesWithSnippets = array();
		$i=0;

		if($codeTypes) {
			foreach($codeTypes as $ct) {
				$res = $this->codeRepository->findCountByCodetype($ct);
				if($res > 0) {
					$codetypesWithSnippets[$i]['count'] = $res;
					$codetypesWithSnippets[$i]['codetype'] = $ct;
					$i++;
				}
			}
		}

		return $codetypesWithSnippets;
		 
	}

	/**
	* currently only used for the importer
	*
	* @param $type
	* @return array
	*/
	public function findByCodetype($type) {
		$query = $this->createQuery();
		$result = $query->matching($query->equals('type', $type))->setLimit(1)->execute();
		return $result->getFirst();
	}

}
?>
