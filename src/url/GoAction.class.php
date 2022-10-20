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
	 * @var string
	 */
	protected $secretConfirm = false;

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

		if (isset($_REQUEST['secretConfirm'])) {
			$this->secretConfirm = true;
		}
	}

	/**
	 * Executes the action.
	 */
	protected function execute() {
		if ($this->shortUrl) {
			$record = $this->urlShortener->fetchData($this->shortUrl);
			if ($record && $record['longUrl']) {
				header("HTTP/1.1 301 Moved Permanently");
				header('Location: ' . $record['longUrl']);
				exit;
			} else if ($record && $record['secret']) {
				if ($this->secretConfirm) {
					$this->urlShortener->deleteById($record['shortUrlID']);
					$this->show('secretData', $record['secret']);
				} else {
					$this->show('secretConfirm');
				}

				return;
			}
		}

		$this->show('notFound');
	}
}