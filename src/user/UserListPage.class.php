<?php
require_once BASE_DIR . 'src/AbstractPage.class.php';

/**
 * Lists users.
 *
 * @author    Magnus Kühn
 * @copyright 2013-2014 Magnus Kühn
 */
class UserListPage extends AbstractPage {
	/**
	 * @var string[]
	 */
	protected $users = array();

	/**
	 * Runs the page.
	 */
	public function run() {
		$this->checkPermissions('admin');
		$this->show('header', 'userList');

		$this->readData();
		if (isset($_GET['deleted'])) {
			$this->show('userDeleted');
		}

		$this->show('userList');
		$this->show('footer');
	}

	/**
	 * Reads data for the page.
	 */
	protected function readData() {
		$sql = "SELECT  *
			FROM    user
			ORDER BY role ASC, username ASC";
		$statement = $this->urlShortener->getDB()->query($sql);
		$this->users = $statement->fetchAll();
	}
}
