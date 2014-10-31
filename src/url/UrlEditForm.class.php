<?php
require_once BASE_DIR . 'src/AbstractPage.class.php';
require_once BASE_DIR . 'src/url/UrlCreateForm.class.php';

/**
 * Edits short urls.
 *
 * @author    Magnus Kühn
 * @copyright 2013-2014 Magnus Kühn
 */
class UrlEditForm extends UrlCreateForm {
	/**
	 * @var string[]
	 */
	protected $urlMapping = array();

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
					FROM    short_url
					WHERE   applicationID = " . $this->urlShortener->getApplicationID() . " AND
							shortUrlID = " . intval($_REQUEST['id']) . "
					LIMIT 1";
			$statement = $this->urlShortener->getDB()->query($sql);
			$this->urlMapping = $statement->fetch();
		}

		// redirect to create if invalid
		if (!$this->urlMapping) {
			header('Location: ' . SERVICE_BASEURL . '/admin/urlCreate.php');
			exit;
		}

		// set current values
		$this->longUrl = $this->urlMapping['longUrl'];
		$this->shortUrl = $this->urlMapping['shortUrl'];
		$this->expire = $this->urlMapping['expire'];
		$this->details = $this->urlMapping['details'];
		$this->protected = $this->urlMapping['protected'];

		parent::run();
	}

	/**
	 * Validates the short url.
	 *
	 * @return bool
	 */
	protected function validateShortUrl() {
		if (!$this->shortUrl) {
			$this->error = array('field' => 'shortUrl', 'error' => 'invalid');
			return false;
		}

		// only check if its unique when it was changed
		if ($this->shortUrl != $this->urlMapping['shortUrl']) {
			return parent::validateShortUrl();
		}

		return true;
	}

	/**
	 * Saves the form.
	 */
	protected function save() {
		$sql = "UPDATE short_url SET longUrl = " . $this->urlShortener->getDB()->quote($this->longUrl) . ", shortUrl = " .
			$this->urlShortener->getDB()->quote($this->shortUrl) . ", expire = " . $this->expire . ", details = " . $this->urlShortener->getDB()->quote($this->details) .
			", protected = " . ($this->protected ? 1 : 0) . " WHERE shortUrlID = " . $this->urlMapping['shortUrlID'];
		$this->urlShortener->getDB()->query($sql);

		$this->show('urlSaved', UrlShortener::expandShortUrl($this->shortUrl));
	}
}
