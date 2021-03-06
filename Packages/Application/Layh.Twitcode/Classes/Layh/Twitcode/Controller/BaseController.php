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

use \TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\Controller\ActionController;

/**
 * Default controller with some basic stuff, each other controller should extend
 * this controller to get functions like the sidebar snippets and the login data
 */
class BaseController extends ActionController {

	/**
	 * @var \Layh\Twitcode\Domain\Model\Login
	 * @Flow\Inject
	 */
	protected $login;

	/**
	 * @var \Layh\Twitcode\Domain\Repository\CodetypeRepository
	 * @Flow\Inject
	 */
	protected $codetypeRepository;

	/**
	 * @var \Layh\Twitcode\Domain\Repository\CodeRepository
	 * @Flow\Inject
	 */
	protected $codeRepository;

	/**
	 * @var array
	 */
	protected $loginData = array();

	/**
	 * @var array
	 */
	protected $settings;

	/*
 	 * Inject the settings
	 *
	 * @param array $settings
	 * @return void
	 */
	public function injectSettings(array $settings) {
		$this->settings = $settings;

		 if (isset($settings['oauth']['consumerkey'])) {
	  		$this->consumerKey = $settings['oauth']['consumerkey'];
	     }
		 if (isset($settings['oauth']['consumersecret'])) {
		    $this->consumerSecret = $settings['oauth']['consumersecret'];
		 }
		 if (isset($settings['bitly']['url'])) {
			$this->baseUrl = $settings['bitly']['url'];
		 }

	}

	/**
	 * Initialize the sidebar and the login
	 *
	 * @return void
	 */
    protected function initSidebarLogin() {
		$this->login->setSettings($this->settings);
	    $this->getLoginData();
        $this->sideBarSnippets();
    }

	/**
	 * Assign the latest snippets to the sidebar
	 *
	 * @return void
	 */
	protected function sideBarSnippets() {
			// get latest snippets
		$latestSnippets = $this->codeRepository->findLatestSnippets(10);
		$this->view->assign('latestSnippets', $latestSnippets);
	}

	/**
	 * Initializes login data
	 *
	 * @return void
	*/
	protected function getLoginData() {
			// check login
		$loginData = array();
		if ($this->login->isLoggedIn()) {
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

?>
