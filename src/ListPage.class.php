<?php
require_once BASE_DIR . 'src/AbstractPage.class.php';

/**
 * Lists URL mappings.
 *
 * @author    Magnus Kühn
 * @copyright 2013-2014 Magnus Kühn
 */
class ListPage extends AbstractPage {
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
	 * Runs the page.
	 */
	public function run() {
		$this->show('header', 'list');

		$this->readParameters();
		$this->readData();
		if (isset($_GET['deleted'])) {
			$this->show('deleted');
		}

		$this->show('list');
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
		$this->urls = $this->urlShortener->getURLs($this->sortField, ($this->sortField == 'createdTime' ? 'DESC' : 'ASC'));
	}
}