<?php
require_once BASE_DIR . 'src/AbstractPage.class.php';
require_once BASE_DIR . 'src/user/UserCreateForm.class.php';

/**
 * Edits users.
 *
 * @author    Magnus Kühn
 * @copyright 2013-2014 Magnus Kühn
 */
class UserEditForm extends UserCreateForm {
	/**
	 * @var string[]
	 */
	protected $user = array();

	/**
	 * @var string
	 */
	protected $action = 'edit';

	/**
	 * Runs the page.
	 */
	public function run() {
		// read mapping
		if (isset($_REQUEST['id'])) {
			$sql = "SELECT  *
					FROM    user
					WHERE   userID = " . intval($_REQUEST['id']) . "
					LIMIT 1";
			$statement = $this->urlShortener->getDB()->query($sql);
			$this->user = $statement->fetch();
		}

		// redirect to create if invalid
		if (!$this->user) {
			header('Location: ' . SERVICE_BASEURL . '/admin/urlCreate.php');
			exit;
		}

		// set current values
		$this->username = $this->user['username'];
		$this->role = $this->user['role'];

		parent::run();
	}

	/**
	 * Validates whether the username is unique.
	 *
	 * @return bool
	 */
	protected function validateUsernameUnique() {
		if ($this->username != $this->user['username']) {
			return parent::validateUsernameUnique();
		}

		return true;
	}

	/**
	 * Saves the form.
	 */
	protected function save() {
		$sql = "UPDATE user SET username = " . $this->urlShortener->getDB()->quote($this->username) . ", password = " .
			$this->urlShortener->getDB()->quote($this->password ? crypt($this->password, '$2y$10$'.bin2hex(openssl_random_pseudo_bytes(22))) : $this->user['password']) . ", role = " .
			$this->urlShortener->getDB()->quote($this->role) ." WHERE userID = " . $this->user['userID'];
		$this->urlShortener->getDB()->query($sql);

		$this->show('userSaved', $this->username);
		$this->password = '';
	}
}
