<?php
require_once BASE_DIR . 'src/UrlShortener.class.php';

/**
 * Abstract implementation for pages.
 *
 * @author    Magnus Kühn
 * @copyright 2013 Magnus Kühn
 */
abstract class AbstractPage {
	/**
	 * @var UrlShortener
	 */
	protected $urlShortener = null;

	/**
	 * Initializes the page.
	 */
	public function __construct() {
		$this->urlShortener = new UrlShortener();
		$this->urlShortener->loadApplication();
	}

	/**
	 * Runs the page.
	 */
	abstract public function run();

	/**
	 * Shows a template.
	 *
	 * @param string $template
	 * @param mixed  $parameter
	 */
	protected function show($template, $parameter = null) {
		$file = BASE_DIR . 'templates/custom/' . $template . '.php';
		if (!file_exists($file)) {
			$file = BASE_DIR . 'templates/' . $template . '.php';
		}

		include $file;
	}

	/**
	 * Checks if the user has admin permissions
	 */
	protected function checkPermissions($role = 'user') {
		$this->urlShortener->loadUser();
		if ($role == 'admin' && $this->urlShortener->getRole() != 'admin') {
			header('Location: ' . SERVICE_BASEURL . 'admin/urlCreate.php');
			exit;
		}
	}
}
