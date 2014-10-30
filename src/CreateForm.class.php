<?php
require_once BASE_DIR . 'src/AbstractPage.class.php';

/**
 * Creates URL mappings.
 *
 * @author    Magnus Kühn
 * @copyright 2013-2014 Magnus Kühn
 */
class CreateForm extends AbstractPage {
	/**
	 * @var string
	 */
	protected $longURL = null;

	/**
	 * @var string
	 */
	protected $shortURL = null;

	/**
	 * @var string[]
	 */
	protected $error = array('field' => null);

	/**
	 * Runs the page.
	 */
	public function run() {
		$this->show('header', 'create');

		if (!empty($_REQUEST)) {
			$this->readParameters();
			$this->validateParameters() && $this->save();
		}

		$this->show('create');
		$this->show('footer');
	}

	/**
	 * Reads parameters for the form.
	 */
	protected function readParameters() {
		if (isset($_REQUEST['longURL'])) {
			$this->longURL = $_REQUEST['longURL'];
		}
		if (isset($_REQUEST['shortURL'])) {
			$this->shortURL = $_REQUEST['shortURL'];
		}
	}

	/**
	 * Validates parameters for the form.
	 */
	protected function validateParameters() {
		// fix long URL
		$this->longURL = $this->urlShortener->stripURL(trim($this->longURL));
		if (!preg_match('~^http[s]?://~', $this->longURL)) {
			$this->longURL = 'http://' . $this->longURL;
		}

		// validate long URL
		if (!preg_match('~^http[s]?://([a-z]+\.){1,2}[a-z]+~i', $this->longURL)) {
			$this->error = array('field' => 'longURL', 'error' => 'notValid');
			return false;
		}

		// validate short URL
		$longURL = $this->urlShortener->expandURL($this->shortURL);
		if ($longURL) {
			$this->error = array('field' => 'shortURL', 'error' => 'taken', 'url' => $longURL);
			return false;
		}

		return true;
	}

	/**
	 * Saves the form.
	 */
	protected function save() {
		$shortURL = $this->urlShortener->save($this->longURL, $this->shortURL);

		$this->show('saved', $shortURL);
		$this->longURL = $this->shortURL = '';
	}
}