<?php
declare(ENCODING = 'utf-8');
namespace F3\Twitcode\Domain\Repository;

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
		$codeTypes = $query->execute();

		$codetypesWithSnippets = array();
		$i=0;

		if($codeTypes)
			foreach($codeTypes as $ct) {
				$res = $this->codeRepository->findCountByCodetype($ct);
				if($res > 0) {
					$codetypesWithSnippets[$i]['codetype'] = $ct;
					$codetypesWithSnippets[$i]['count'] = $res;
					$i++;
				}
			}

		return $codetypesWithSnippets;
		 
	}

    /**
     * currently only used for the importer
     *
     * @param  $type
     * @return array
     */
	public function findByCodetype($type) {
		$query = $this->createQuery();
		$result = $query->matching($query->equals('type', $type))->setLimit(1)->execute();
		return $result->getFirst();
	}

}
?>
