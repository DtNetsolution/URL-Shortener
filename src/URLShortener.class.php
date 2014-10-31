<?php

/**
 * Main class for the url shortener.
 *
 * @author    Magnus Kühn
 * @copyright 2013-2014 Magnus Kühn
 */
class UrlShortener {
	/**
	 * @var string[]
	 */
	public static $roles = array('admin' => 'Administrator', 'user' => 'Benutzer');

	/**
	 * @var PDO
	 */
	protected $db;

	/**
	 * stripping regex list
	 *
	 * @var string[]
	 */
	protected $stripRegex = array();

	/**
	 * current user
	 *
	 * @var string[]
	 */
	protected $user = array('userID' => 0, 'role' => 'user');

	/**
	 * current application
	 *
	 * @var string
	 */
	protected $application = array();

	/**
	 * Initializes the url shortener.
	 */
	public function __construct() {
		$databaseHost = $databaseDB = $databaseUser = $databasePassword = '';
		include BASE_DIR . 'config/config.php';
		$this->db = new PDO('mysql:host=' . $databaseHost . ';dbname=' . $databaseDB, $databaseUser, $databasePassword);
	}

	/**
	 * Loads the current user and requests credentials if necessary.
	 */
	public function loadUser() {
		// validate credentials
		if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
			$sql = "SELECT * FROM user WHERE username = " . $this->db->quote($_SERVER['PHP_AUTH_USER']);
			$statement = $this->db->query($sql);
			$user = $statement->fetch();

			if ($user && crypt($_SERVER['PHP_AUTH_PW'], $user['password']) == $user['password']) {
				$this->user = $user;
			}
		}

		// ask for credentials if no user
		if (!$this->user['userID']) {
			$this->cleanupUrls();
			header('WWW-Authenticate: Basic realm="URL Shortener Login"');
			header('HTTP/1.0 401 Unauthorized');
			echo 'Authentication Required';
			exit;
		}
	}

	/**
	 * Cleans up old urls.
	 */
	protected function cleanupUrls() {
		$sql = "DELETE FROM short_url WHERE expire <= " . time();
		$this->db->exec($sql);
	}

	/**
	 * Loads the current application.
	 */
	public function loadApplication() {
		$sql = "SELECT * FROM application WHERE domainHost = " . $this->db->quote($_SERVER['HTTP_HOST']);
		$statement = $this->db->query($sql);
		if (!$statement->rowCount()) {
			die('Keine Application f&uuml;r die Domain "' . $_SERVER['HTTP_HOST'] . '".');
		}

		// store application
		$this->application = $statement->fetch();
		define('SERVICE_BASEURL', 'http://' . $this->application['domainHost'] . $this->application['domainPath']);
	}

	/**
	 * Returns the database.
	 *
	 * @return \PDO
	 */
	public function getDB() {
		return $this->db;
	}

	/**
	 * Returns the user id.
	 *
	 * @return int
	 */
	public function getUserID() {
		return $this->user['userID'];
	}

	/**
	 * Returns the user role.
	 *
	 * @return string
	 */
	public function getRole() {
		return $this->user['role'];
	}

	/**
	 * Returns the application id.
	 *
	 * @return int
	 */
	public function getApplicationID() {
		return $this->application['applicationID'];
	}

	/**
	 * Expands a shortened url into its full version.
	 *
	 * @param string $shortUrl
	 * @return string
	 */
	public function expandUrl($shortUrl) {
		$sql = "SELECT longUrl FROM short_url WHERE applicationID = " . $this->application['applicationID'] . " AND shortUrl = " . $this->db->quote($shortUrl) . " LIMIT 1";
		$statement = $this->db->query($sql);
		$row = $statement->fetch();

		return ($row ? $row['longUrl'] : null);
	}

	/**
	 * Strips an url.
	 *
	 * @param string $url
	 * @return string
	 */
	public function stripUrl($url) {
		foreach ($this->stripRegex as $regex) {
			$url = preg_replace($regex, '', $url);
		}

		return $url;
	}

	/**
	 * Adds a strip regex.
	 *
	 * @param string $regex
	 */
	public function addStripRegex($regex) {
		$this->stripRegex[] = $regex;
	}

	/**
	 * Returns the expanded short url.
	 *
	 * @param string $shortUrl
	 * @return string
	 */
	public static function expandShortUrl($shortUrl) {
		return SERVICE_BASEURL . '?' . $shortUrl;
	}
}