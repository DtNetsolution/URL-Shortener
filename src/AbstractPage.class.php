<?php
require_once BASE_DIR . 'src/URLShortener.class.php';

/**
 * Abstract implementation for pages.
 *
 * @author    Magnus Kühn
 * @copyright 2013 Magnus Kühn
 */
abstract class AbstractPage {
	/**
	 * @var URLShortener
	 */
	protected $urlShortener = null;

	/**
	 * Initializes the form.
	 */
	public function __construct() {
		$this->urlShortener = new URLShortener();
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
	protected function show($template, /** @noinspection PhpUnusedParameterInspection */
	                        $parameter = null) {
		/** @noinspection PhpIncludeInspection */
		include BASE_DIR . 'templates/' . $template . '.php';
	}
}