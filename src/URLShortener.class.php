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
	protected $user = array();

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
		if (!$this->user) {
			header('WWW-Authenticate: Basic realm="URL Shortener Login"');
			header('HTTP/1.0 401 Unauthorized');
			echo 'Authentication Required';
			exit;
		}
	}

	/**
	 * Returns the user role
	 *
	 * @return string
	 */
	public function getRole() {
		return (isset($this->user['role']) ? $this->user['role'] : 'user');
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
	 * Fetches a short url.
	 *
	 * @param int $shortUrlID
	 * @return null|string[]
	 */
	public function getShortUrl($shortUrlID) {
		$sql = "SELECT * FROM short_url WHERE applicationID = " . $this->application['applicationID'] . " AND shortUrlID = " . $shortUrlID . " LIMIT 1";
		$statement = $this->db->query($sql);
		return $statement->fetch();
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
	 * Fetches a list of all urls.
	 *
	 * @param string $sortField
	 * @param string $sortOrder
	 * @return string[]
	 */
	public function getUrls($sortField, $sortOrder) {
		$sql = "SELECT  short_url.*, user.username as creator
				FROM    short_url
				JOIN    user ON user.userID = short_url.userID
				WHERE   applicationID = " . $this->application['applicationID'] . "
				ORDER BY " . $sortField . " " . $sortOrder;
		$statement = $this->db->query($sql);
		return $statement->fetchAll();
	}

	/**
	 * Creates a new short url.
	 *
	 * @param string $longUrl
	 * @param string $shortUrl
	 * @param int    $expire
	 * @param string $details
	 * @param bool   $protected
	 * @return string
	 */
	public function createUrl($longUrl, $shortUrl = null, $expire = 0, $details = '', $protected = false) {
		if (!$shortUrl) {
			do {
				$shortUrl = mt_rand(11111, 99999);
				return $shortUrl;
			} while ($this->expandUrl($shortUrl));
		}

		$sql = "INSERT INTO short_url (applicationID, longUrl, shortUrl, userID, createdTime, expire, details, protected) VALUES
			(" . $this->application['applicationID'] . ", " . $this->db->quote($longUrl) . ", " . $this->db->quote($shortUrl) . ", " . $this->db->quote($this->user['userID']) .
			", " . time() . ", " . ($expire > 0 ? $expire : 'Null') . ", " . $this->db->quote($details) . ", " . ($protected ? 1 : 0) . ")";
		$this->db->query($sql);

		return self::expandShortUrl($shortUrl);
	}

	/**
	 * Updates a short url.
	 *
	 * @param int    $shortUrlID
	 * @param string $longUrl
	 * @param string $shortUrl
	 * @param int    $expire
	 * @param string $details
	 * @param bool   $protected
	 */
	public function updateUrl($shortUrlID, $longUrl, $shortUrl, $expire, $details, $protected) {
		$sql = "UPDATE short_url SET longUrl = " . $this->db->quote($longUrl) . ", shortUrl = " . $this->db->quote($shortUrl) . ", expire = " . $expire .
			", details = " . $this->db->quote($details) . ", protected = " . ($protected ? 1 : 0) . " WHERE shortUrlID = " . $shortUrlID;
		$this->db->query($sql);
	}

	/**
	 * Deletes a short url.
	 *
	 * @param integer $shortUrlID
	 * @return boolean
	 */
	public function deleteUrl($shortUrlID) {
		$sql = "DELETE FROM short_url WHERE applicationID = " . $this->application['applicationID'] . " AND shortUrlID = " . intval($shortUrlID) . " AND protected = 0";
		$statement = $this->db->query($sql);
		return $statement->rowCount() > 0;
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
	 * Fetches a list of all users.
	 */
	public function getUsers() {
		$sql = "SELECT * FROM user ORDER BY role ASC, username ASC";
		$statement = $this->db->query($sql);
		return $statement->fetchAll();
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