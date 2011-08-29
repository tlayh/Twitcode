<?php
namespace Layh\Twitcode\Controller;

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

class CodelibController extends \Layh\Twitcode\Controller\BaseController {

	/**
	 * @var Layh\Twitcode\Domain\Repository\CodeRepository
	 * @inject
	 */
	protected $codeRepository;

	/**
	 * @var Layh\Twitcode\Domain\Repository\CodetypeRepository
	 * @inject
	 */
	protected $codetypeRepository;

	public function indexAction() {
		$this->initSidebarLogin();

		$codeTypes = $this->codetypeRepository->findAllWithSnippets();
		$this->view->assign('codetypes', $codeTypes);
	}

	/**
	 * show snippets by users
	 *
	 * @deprecated
	 * @return void
	 * @author Thomas Layh <develop@layh.com>
	 */
	public function showbyuserAction() {
		$this->initSidebarLogin();

		if($this->login->isLoggedIn()) {
			$loginData = $this->login->checkSession();
			$snippets = $this->codeRepository->findByUser($loginData['user_id']);
			$this->view->assign('snippets', $snippets);
		} else {
			$this->flashMessageContainer->add('No User logged in!!');
		}
	}

	/**
	 * @param \Layh\Twitcode\Domain\Model\Codetype $codetype
	 * @return void
	 */
	public function showbytypeAction(\Layh\Twitcode\Domain\Model\Codetype $codetype) {
        $this->initSidebarLogin();

		$snippets = $this->codeRepository->findByCodetype($codetype);

		$this->view->assign('codetype', $codetype);
		$this->view->assign('snippets', $snippets);
	}

}
