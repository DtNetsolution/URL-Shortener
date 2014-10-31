<?php
require_once BASE_DIR . 'src/AbstractPage.class.php';

/**
 * Creates a short url.
 *
 * @author    Magnus Kühn
 * @copyright 2013-2014 Magnus Kühn
 */
class UrlCreateForm extends AbstractPage {
	/**
	 * @var string
	 */
	protected $longUrl = '';

	/**
	 * @var string
	 */
	protected $shortUrl = '';

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
	protected $protected = false;

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
		$this->show('header', 'urlCreate');

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
		if (isset($_POST['longUrl'])) $this->longUrl = $_POST['longUrl'];
		if (isset($_POST['shortUrl'])) $this->shortUrl = $_POST['shortUrl'];
		if (isset($_POST['expire'])) $this->expire = intval($_POST['expire']);
		if (isset($_POST['details'])) $this->details = $_POST['details'];
		$this->protected = isset($_POST['protected']);
	}

	/**
	 * Validates parameters for the form.
	 *
	 * @return bool
	 */
	protected function validateParameters() {
		// fix long url
		$this->longUrl = $this->urlShortener->stripUrl(trim($this->longUrl));
		if (!preg_match('~^http[s]?://~', $this->longUrl)) {
			$this->longUrl = 'http://' . $this->longUrl;
		}

		// validate long url
		if (!preg_match('~^http[s]?://([a-z]+\.){1,2}[a-z]+~i', $this->longUrl)) {
			$this->error = array('field' => 'longUrl', 'error' => 'notValid');
			return false;
		}

		// validate short url
		return $this->validateShortUrl();
	}

	/**
	 * Validates the short url.
	 *
	 * @return bool
	 */
	protected function validateShortUrl() {
		$longUrl = $this->urlShortener->expandUrl($this->shortUrl);
		if ($longUrl) {
			$this->error = array('field' => 'shortUrl', 'error' => 'taken', 'url' => $longUrl);
			return false;
		}

		return true;
	}

	/**
	 * Saves the form.
	 */
	protected function save() {
		$shortUrl = $this->urlShortener->createUrl($this->longUrl, $this->shortUrl, $this->expire, $this->details, $this->protected);

		$this->show('urlSaved', $shortUrl);
		$this->longUrl = $this->shortUrl = $this->details = '';
		$this->expire = 0;
		$this->protected = false;
	}
}