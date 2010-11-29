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

	public function findAllWithSnippets() {
		$query = $this->createQuery();
		$codeTypes = $query
				->matching($query->logicalNot($query->equals('type', '')))
				->setOrderings(array('name' => \F3\FLOW3\Persistence\QueryInterface::ORDER_ASCENDING))
				->execute();

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

	public function findByCodetype($type) {
		$query = $this->createQuery();
		$result = $query->matching($query->equals('type', $type))->setLimit(1)->execute();
		return $result->getFirst();
	}

}
?>
