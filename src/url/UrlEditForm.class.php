<?php
require_once BASE_DIR . 'src/AbstractPage.class.php';
require_once BASE_DIR . 'src/url/UrlCreateForm.class.php';

/**
 * Edits URL mappings.
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
			$this->urlMapping = $this->urlShortener->getUrlMapping(intval($_REQUEST['id']));
		}

		// redirect to create if invalid
		if (!$this->urlMapping) {
			header('Location: ' . SERVICE_BASEURL . '/admin/create.php');
			exit;
		}

		// set current values
		$this->longURL = $this->urlMapping['longUrl'];
		$this->shortURL = $this->urlMapping['shortUrl'];
		$this->expire = $this->urlMapping['expire'];
		$this->details = $this->urlMapping['details'];
		$this->protect = $this->urlMapping['protect'];

		parent::run();
	}

	/**
	 * Validates the short url.
	 *
	 * @return bool
	 */
	protected function validateShortUrl() {
		if ($this->shortURL != $this->urlMapping['shortUrl']) {
			return parent::validateShortUrl();
		}

		return true;
	}

	/**
	 * Saves the form.
	 */
	protected function save() {
		$this->urlShortener->updateUrl($this->urlMapping['shortUrlID'], $this->longURL, $this->shortURL, $this->expire, $this->details, $this->protect);
		$this->show('urlUpdated', URLShortener::expandShortURL($this->shortURL));
	}
}
