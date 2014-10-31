<?php
require_once BASE_DIR . 'src/AbstractPage.class.php';

/**
 * Redirects a short url to its long form.
 *
 * @author    Magnus Kühn
 * @copyright 2013-2014 Magnus Kühn
 */
class GoAction extends AbstractPage {
	/**
	 * @var string
	 */
	protected $shortUrl = null;

	/**
	 * Runs the page.
	 */
	public function run() {
		$this->readParameters();
		$this->execute();
	}

	/**
	 * Reads parameters for the form.
	 */
	protected function readParameters() {
		if (isset($_SERVER['QUERY_STRING'])) {
			$this->shortUrl = $_SERVER['QUERY_STRING'];
		}

		if (isset($_REQUEST['url'])) {
			$this->shortUrl = $_REQUEST['url'];
		}
	}

	/**
	 * Executes the action.
	 */
	protected function execute() {
		if ($this->shortUrl) {
			$longUrl = $this->urlShortener->expandUrl($this->shortUrl);
			if ($longUrl) {
				header("HTTP/1.1 301 Moved Permanently");
				header('Location: ' . $longUrl);
				exit;
			}
		}

		$this->show('notFound');
	}
}