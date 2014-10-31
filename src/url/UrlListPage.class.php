<?php
require_once BASE_DIR . 'src/AbstractPage.class.php';

/**
 * Lists short urls.
 *
 * @author    Magnus Kühn
 * @copyright 2013-2014 Magnus Kühn
 */
class UrlListPage extends AbstractPage {
	/**
	 * @var string[]
	 */
	protected $urls = array();

	/**
	 * @var string
	 */
	protected $sortField = 'createdTime';

	/**
	 * @var string[]
	 */
	protected $validSortFields = array('shortUrlID', 'shortUrl', 'longUrl', 'creator', 'createdTime');

	/**
	 * Initializes the page.
	 */
	public function __construct() {
		$this->urlShortener = new UrlShortener();
		if (!LIST_URLS_NO_USER) $this->urlShortener->loadUser();
		$this->urlShortener->loadApplication();
	}

	/**
	 * Runs the page.
	 */
	public function run() {
		$this->show('header', 'urlList');

		$this->readParameters();
		$this->readData();
		if (isset($_GET['deleted'])) {
			$this->show('urlDeleted');
		}

		$this->show('urlList');
		$this->show('footer');
	}

	/**
	 * Reads parameters for the form.
	 */
	protected function readParameters() {
		if (isset($_GET['sortField']) && in_array($_GET['sortField'], $this->validSortFields)) {
			$this->sortField = $_GET['sortField'];
		}
	}

	/**
	 * Reads data for the page.
	 */
	protected function readData() {
		$sql = "SELECT  short_url.*, user.username as creator
				FROM    short_url
				JOIN    user ON user.userID = short_url.userID
				WHERE   applicationID = " . $this->urlShortener->getApplicationID() . "
				ORDER BY " . $this->sortField . " " . ($this->sortField == 'createdTime' ? 'DESC' : 'ASC');
		$statement = $this->urlShortener->getDB()->query($sql);
		$this->urls = $statement->fetchAll();
	}
}