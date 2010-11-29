<?php
declare(ENCODING = 'utf-8');
namespace F3\Twitcode\Controller;

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

class CodelibController extends \F3\Twitcode\Controller\DefaultController {

	/**
	 * @var F3\Twitcode\Domain\Repository\CodeRepository
	 * @inject
	 */
	protected $codeRepository;

	/**
	 * @var F3\Twitcode\Domain\Repository\CodetypeRepository
	 * @inject
	 */
	protected $codetypeRepository;

	public function indexAction() {
		$this->getLoginData();
        $this->sideBarSnippets();
        
		$codeTypes = $this->codetypeRepository->findAllWithSnippets();
		$this->view->assign('codetypes', $codeTypes);
	}

	public function showbyuserAction() {
		$this->getLoginData();
        $this->sideBarSnippets();
        
		if($this->login->isLoggedIn()) {
			$loginData = $this->login->checkSession();
			$snippets = $this->codeRepository->findByUser($loginData['user_id']);
			$this->view->assign('snippets', $snippets);
		} else {
			$this->flashMessageContainer->add('No User logged in!!');
		}
	}

	/**
	 * @param \F3\Twitcode\Domain\Model\Codetype $codetype
	 * @return void
	 */
	public function showbytypeAction(\F3\Twitcode\Domain\Model\Codetype $codetype) {
        $this->getLoginData();
        $this->sideBarSnippets();
        
		$snippets = $this->codeRepository->findByCodetype($codetype);
		$this->view->assign('codetype', $codetype);
		$this->view->assign('snippets', $snippets);
	}

	/**
	 * Initializes login data
	 *
	 * @return void
	*/
	protected function getLoginData() {
		// check login
		$loginData = array();
		if($this->login->isLoggedIn()) {
			// get Login data
			$loginData['data'] = $this->login->checkSession();
			$loginData['loggedin'] = true;
		} else {
			// get Login Url
			$loginData['loggedin'] = false;
			$loginData['url'] = $this->login->getLoginUrl();
		}
		$this->view->assign('logindata', $loginData);
	}

}
