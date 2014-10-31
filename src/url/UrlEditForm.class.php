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
			$this->urlMapping = $this->urlShortener->getShortUrl(intval($_REQUEST['id']));
		}

		// redirect to create if invalid
		if (!$this->urlMapping) {
			header('Location: ' . SERVICE_BASEURL . '/admin/create.php');
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
		if ($this->shortUrl != $this->urlMapping['shortUrl']) {
			return parent::validateShortUrl();
		}

		return true;
	}

	/**
	 * Saves the form.
	 */
	protected function save() {
		$this->urlShortener->updateUrl($this->urlMapping['shortUrlID'], $this->longUrl, $this->shortUrl, $this->expire, $this->details, $this->protected);
		$this->show('urlUpdated', UrlShortener::expandShortUrl($this->shortUrl));
	}
}
