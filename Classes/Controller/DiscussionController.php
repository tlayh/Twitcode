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

/**
 * Discussion controller for the Twitcode package
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class DiscussionController extends \Layh\Twitcode\Controller\BaseController {

	/**
	 * @var \Layh\Twitcode\Domain\Repository\DiscussionRepository
	 * @inject
	 */
	protected $discussionRepository;

	/**
	 * Save the discussion
	 *
	 * @param \Layh\Twitcode\Domain\Model\Discussion $discussion
	 * @return void
	 * @author Thomas Layh <develop@layh.com>
	 */
	public function saveAction(\Layh\Twitcode\Domain\Model\Discussion $discussion, \Layh\Twitcode\Domain\Model\Code $code, \Layh\Twitcode\Domain\Model\User $user) {

		// add code and user to discussion and add the discussion to the repository
		$discussion->setCode($code);
		$discussion->setUser($user);
		$this->discussionRepository->add($discussion);

		$this->redirect('show', 'Standard', NULL, array('code' => $code));
	}

}
