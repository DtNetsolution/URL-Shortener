<?php
require_once BASE_DIR . 'src/AbstractPage.class.php';

/**
 * Creates URL mappings.
 *
 * @author    Magnus Kühn
 * @copyright 2013-2014 Magnus Kühn
 */
class UrlCreateForm extends AbstractPage {
	/**
	 * @var string
	 */
	protected $longURL = '';

	/**
	 * @var string
	 */
	protected $shortURL = '';

	/**
	 * @var int
	 */
	protected $expire = 0;

	/**
	 * @var string
	 */
	protected $details = '';

	/**
	 * @var bool
	 */
	protected $protect = false;

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
		$this->show('header', 'create');

		if (!empty($_POST)) {
			$this->readParameters();
			$this->validateParameters() && $this->save();
		}

		$this->show('urlCreate');
		$this->show('footer');
	}

	/**
	 * Reads parameters for the form.
	 */
	protected function readParameters() {
		if (isset($_POST['longURL'])) $this->longURL = $_POST['longURL'];
		if (isset($_POST['shortURL'])) $this->shortURL = $_POST['shortURL'];
		if (isset($_POST['expire'])) $this->expire = intval($_POST['expire']);
		if (isset($_POST['details'])) $this->details = $_POST['details'];
		if (isset($_POST['protect'])) $this->protect = true;
	}

	/**
	 * Validates parameters for the form.
	 *
	 * @return bool
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
		return $this->validateShortUrl();
	}

	/**
	 * Validates the short url.
	 *
	 * @return bool
	 */
	protected function validateShortUrl() {
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
		$shortURL = $this->urlShortener->createUrl($this->longURL, $this->shortURL, $this->expire, $this->details, $this->protect);

		$this->show('urlSaved', $shortURL);
		$this->longURL = $this->shortURL = $this->details = '';
		$this->expire = 0;
		$this->protect = false;
	}
}