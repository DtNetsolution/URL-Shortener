<?php
require_once BASE_DIR . 'src/AbstractPage.class.php';

/**
 * Creates an user.
 *
 * @author    Magnus Kühn
 * @copyright 2013-2014 Magnus Kühn
 */
class UserCreateForm extends AbstractPage {
	/**
	 * @var string
	 */
	protected $username = '';

	/**
	 * @var string
	 */
	protected $password = '';

	/**
	 * @var string
	 */
	protected $role = 'user';

	/**
	 * @var string
	 */
	protected $action = 'create';

	/**
	 * @var string[]
	 */
	protected $error = array('field' => null);

	/**
	 * Runs the page.
	 */
	public function run() {
		$this->checkAdminPermissions();
		$this->show('header', 'userCreate');

		if (!empty($_POST)) {
			$this->readParameters();
			$this->validateParameters() && $this->save();
		}

		$this->show('userCreate');
		$this->show('footer');
	}

	/**
	 * Reads parameters for the form.
	 */
	protected function readParameters() {
		if (isset($_POST['username'])) $this->username = $_POST['username'];
		if (isset($_POST['password'])) $this->password = $_POST['password'];
		if (isset($_POST['role']) && array_key_exists($_POST['role'], UrlShortener::$roles)) $this->role = $_POST['role'];
	}

	/**
	 * Validates parameters for the form.
	 *
	 * @return bool
	 */
	protected function validateParameters() {
		// validate username
		if (empty($this->username)) {
			$this->error = array('field' => 'username', 'error' => 'notValid');
			return false;
		}

		// validate password
		if ($this->action == 'create' && empty($this->password)) {
			$this->error = array('field' => 'password', 'error' => 'notValid');
			return false;
		}

		return $this->validateUsernameUnique();
	}

	/**
	 * Validates whether the username is unique.
	 *
	 * @return bool
	 */
	protected function validateUsernameUnique() {
		$sql = "SELECT  *
				FROM    user
				WHERE	username = " . $this->urlShortener->getDB()->quote($this->username);
		$statement = $this->urlShortener->getDB()->query($sql);
		if ($statement->fetch()) {
			$this->error = array('field' => 'username', 'error' => 'taken');
			return false;
		}

		return true;
	}

	/**
	 * Saves the form.
	 */
	protected function save() {
		$sql = "INSERT INTO user (username, password, role) VALUES (" . $this->urlShortener->getDB()->quote($this->username) . ", " .
			$this->urlShortener->getDB()->quote(crypt($this->password)) . ", " . $this->urlShortener->getDB()->quote($this->role) . ")";
		$this->urlShortener->getDB()->query($sql);

		$this->show('userCreated', $this->username);
		$this->username = $this->password = '';
		$this->role = 'user';
	}
}