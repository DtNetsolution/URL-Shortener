<?php
require_once BASE_DIR . 'src/AbstractPage.class.php';

/**
 * Deletes an user.
 *
 * @author    Magnus Kühn
 * @copyright 2013-2014 Magnus Kühn
 */
class UserDeleteAction extends AbstractPage {
	/**
	 * Runs the page.
	 */
	public function run() {
		$this->checkPermissions('admin');
		$this->execute();

		header('Location: ' . SERVICE_BASEURL . 'admin/userList.php');
		exit;
	}

	/**
	 * Executes the action.
	 */
	public function execute() {
		if (!empty($_GET['id'])) {
			// disallow deleting yourself
			if (intval($_GET['id']) == $this->urlShortener->getUserID()) {
				return;
			}

			// delete user
			$sql = "DELETE FROM user
					WHERE   userID = " . intval($_GET['id']);
			$this->urlShortener->getDB()->query($sql);

			header('Location: ' . SERVICE_BASEURL . 'admin/userList.php?deleted');
			exit;
		}
	}
}
