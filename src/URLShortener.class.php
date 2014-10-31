<?php

/**
 * Main class for the url shortener.
 *
 * @author    Magnus Kühn
 * @copyright 2013-2014 Magnus Kühn
 */
class UrlShortener {
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
	 * application
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
	 * Fetches an url mapping
	 *
	 * @param int $shortUrlID
	 * @return null|string[]
	 */
	public function getUrlMapping($shortUrlID) {
		$sql = "SELECT * FROM short_url WHERE applicationID = " . $this->application['applicationID'] . " AND shortUrlID = " . $shortUrlID . " LIMIT 1";
		$statement = $this->db->query($sql);
		return $statement->fetch();
	}

	/**
	 * Expands a shortened url into the full one.
	 *
	 * @param string $shortUrl
	 * @return string
	 */
	public function expandUrl($shortUrl) {
		$sql = "SELECT longUrl FROM short_url WHERE applicationID = " . $this->application['applicationID'] . " AND shortUrl = " . $this->db->quote($shortUrl) . " LIMIT 1";
		$statement = $this->db->query($sql);
		$row = $statement->fetch();

		if ($row) {
			return $row['longUrl'];
		} else {
			return null;
		}
	}

	/**
	 * Fetches a list of all Urls.
	 *
	 * @param string $sortField
	 * @param string $sortOrder
	 * @return string[]
	 */
	public function getUrls($sortField, $sortOrder) {
		$sql = "SELECT * FROM short_url WHERE applicationID = " . $this->application['applicationID'] . " ORDER BY " . $sortField . " " . $sortOrder;
		$statement = $this->db->query($sql);
		return $statement->fetchAll();
	}

	/**
	 * Creates an url mapping.
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
			} while ($this->expandUrl($shortUrl));
		}

		$user = (isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : '');
		$sql = "INSERT INTO short_url (applicationID, longUrl, shortUrl, creator, createdTime, expire, details, protected) VALUES
			(" . $this->application['applicationID'] . ", " . $this->db->quote($longUrl) . ", " . $this->db->quote($shortUrl) . ", " . $this->db->quote($user) . ", " .
			time() . ", " . ($expire > 0 ? time() + $expire * 24 * 60 * 60 : 'Null') . ", " . $this->db->quote($details) . ", " . ($protected ? 1 : 0) . ")";
		$this->db->query($sql);

		return self::expandShortUrl($shortUrl);
	}

	/**
	 * Updates an url mapping.
	 *
	 * @param int    $shortUrlID
	 * @param string $longUrl
	 * @param string $shortUrl
	 * @param int    $expire
	 * @param string $details
	 * @param bool   $protected
	 * @return string
	 */
	public function updateUrl($shortUrlID, $longUrl, $shortUrl, $expire, $details, $protected) {
		$sql = "UPDATE short_url SET longUrl = " . $this->db->quote($longUrl) . ", shortUrl = " . $this->db->quote($shortUrl) . ", details = " . $this->db->quote($details) .
			", protected = " . ($protected ? 1 : 0) . " WHERE shortUrlID = " . $shortUrlID;
		$this->db->query($sql);
	}

	/**
	 * Deletes an url mapping.
	 *
	 * @param integer $shortUrlID
	 * @return boolean
	 */
	public function deleteUrl($shortUrlID) {
		$sql = "DELETE FROM short_url WHERE applicationID = " . $this->application['applicationID'] . " AND shortUrlID = " . intval($shortUrlID);
		return (bool)$this->db->exec($sql);
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