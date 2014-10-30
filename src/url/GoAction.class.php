<?php
require_once BASE_DIR . 'src/AbstractPage.class.php';

/**
 * Redirects based on an URL mapping.
 *
 * @author    Magnus Kühn
 * @copyright 2013-2014 Magnus Kühn
 */
class GoAction extends AbstractPage {
	/**
	 * @var string
	 */
	protected $shortURL = null;

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
			$this->shortURL = $_SERVER['QUERY_STRING'];
		}

		if (isset($_REQUEST['url'])) {
			$this->shortURL = $_REQUEST['url'];
		}
	}

	/**
	 * Executes the action.
	 */
	protected function execute() {
		if ($this->shortURL) {
			$longURL = $this->urlShortener->expandURL($this->shortURL);
			if ($longURL) {
				header("HTTP/1.1 301 Moved Permanently");
				header('Location: ' . $longURL);
				exit;
			}
		}

		$this->show('notFound');
	}
}