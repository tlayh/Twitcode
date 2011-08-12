<?php
namespace Layh\Twitcode\Controller;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2011 Thomas Layh <info@twitcode.org>
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
 * Import controller for the Twitcode package
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class ImportController extends \TYPO3\FLOW3\MVC\Controller\ActionController {

	/**
	 * @var Layh\Twitcode\Domain\Repository\CodeRepository
	 * @inject
	 */
	protected $codeRepository;

	/**
	 * @var Layh\Twitcode\Domain\Repository\CodetypeRepository
	 * @inject
	 */
	protected $codeTypeRepository;

	/**
	 * @var Layh\Twitcode\Domain\Repository\UserRepository
	 * @inject
	 */
	protected $userRepository;

	/**
	 * indexAction
	 * @return void
	 */
	public function indexAction() {

		//$this->importCodeType();
		$string = 'CodeType import finished';

		//$this->importUser();
		$string = '\nUser import finished';

		$this->importCode();
		$string = '\nCode import finished';

		return $string;

	}

	/**
	 * @return void
	 */
	protected function importCode() {
		$jsonString = \file_get_contents('code.json');

		$data = json_decode($jsonString);

		// remove all code
		$this->codeRepository->removeAll();

		echo "<pre>";
		foreach($data as $da) {
			print_r($da);

			// resolve user and codetype for code object
			$user = $this->userRepository->findOneByUser_id($da->user);
			$codetype = $this->codeTypeRepository->findOneByType($da->codetype);
			$code = new \Layh\Twitcode\Domain\Model\Code();
			$code->setCodetype($codetype);
			$code->setUser($user);

			$code->setCode($da->code);
			$code->setComment($da->comment);
			$code->setLabel($da->label);
			$code->setUid($da->uid);

			$modified = new \DateTime($da->modified->date);
			$code->setModified($modified);

			$this->codeRepository->add($code);

		}

	}

	/**
	 * @return void
	 */
	protected function importUser() {
		$jsonString = \file_get_contents('users.json');

		$data = json_decode($jsonString);

		// remove all users
		$this->userRepository->removeAll();

		foreach($data as $da) {
			$user = new \Layh\Twitcode\Domain\Model\User();
			$user->setName($da->name);
			$user->setUserId($da->user_id);
			$this->userRepository->add($user);
		}

	}

	/**
	 * @return void
	 */
	protected function importCodeType() {
		$jsonString = \file_get_contents('codetypes.json');

		$data = json_decode($jsonString);

		// remove all codetypes
		$this->codeTypeRepository->removeAll();

		foreach($data as $da) {
			$codetype = new \Layh\Twitcode\Domain\Model\Codetype();
			$codetype->setName($da->name);
			$codetype->setType($da->type);
			$this->codeTypeRepository->add($codetype);
		}

	}

}
