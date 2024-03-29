<?php
require_once BASE_DIR . 'src/AbstractPage.class.php';

/**
 * Deletes a short url.
 *
 * @author    Magnus Kühn
 * @copyright 2013-2014 Magnus Kühn
 */
class UrlDeleteAction extends AbstractPage {
	/**
	 * Runs the page.
	 */
	public function run() {
		$this->checkPermissions();
		$this->execute();

		header('Location: ' . SERVICE_BASEURL . 'admin/');
		exit;
	}

	/**
	 * Executes the action.
	 */
	public function execute() {
		if (!empty($_GET['id'])) {
			$this->urlShortener->deleteById(intval($_GET['id']));
			header('Location: ' . SERVICE_BASEURL . 'admin/?deleted');
			exit;
		}
	}
}
