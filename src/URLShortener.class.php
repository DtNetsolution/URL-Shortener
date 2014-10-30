<?php

/**
 * Database abstraction layer for the table `url_map`.
 *
 * @author    Magnus Kühn
 * @copyright 2013-2014 Magnus Kühn
 */
class URLShortener {
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
	 * Initializes the URL shortener.
	 */
	public function __construct() {
		$databaseHost = $databaseDB = $databaseUser = $databasePassword = '';
		include BASE_DIR . 'config/config.php';
		$this->db = new PDO('mysql:host=' . $databaseHost . ';dbname=' . $databaseDB, $databaseUser, $databasePassword);
	}

	/**
	 * Loads the application
	 */
	public function loadApplication() {
		$sql = "SELECT * FROM application WHERE domainHost = " . $this->db->quote($_SERVER['HTTP_HOST']);
		$statement = $this->db->query($sql);
		if (!$statement->rowCount()) {
			die('Keine Application f&uuml;r die Domain "' . $_SERVER['HTTP_HOST'] . '" gefunden.');
		}

		// store application
		$this->application = $statement->fetch();
		define('SERVICE_BASEURL', 'http://' . $this->application['domainHost'] . $this->application['domainPath']);
	}

	/**
	 * Expands a shortened url into the full one.
	 *
	 * @param string $shortURL
	 * @return string
	 */
	public function expandURL($shortURL) {
		$sql = "SELECT longURL FROM url_map WHERE shortURL = " . $this->db->quote($shortURL) . " LIMIT 1";
		$statement = $this->db->query($sql);
		$row = $statement->fetch();

		if ($row) {
			return $row['longURL'];
		} else {
			return null;
		}
	}

	/**
	 * Fetches a list of all URLs.
	 *
	 * @param string $sortField
	 * @param string $sortOrder
	 * @return string[]
	 */
	public function getURLs($sortField, $sortOrder) {
		$sql = "SELECT * FROM url_map WHERE applicationID = " . $this->application['applicationID'] . " ORDER BY " . $sortField . " " . $sortOrder;
		$statement = $this->db->query($sql);
		return $statement->fetchAll();
	}

	/**
	 * Saves an url mapping.
	 *
	 * @param string $longURL
	 * @param string $shortURL
	 * @return string
	 */
	public function save($longURL, $shortURL = null) {
		if (!$shortURL) {
			do {
				$shortURL = mt_rand(11111, 99999);
			} while ($this->expandURL($shortURL));
		}

		$user = (isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : '');
		$sql = "INSERT INTO url_map (applicationID, longURL, shortURL, creator, createdTime) VALUES
			(" . $this->application['applicationID'] . ", " . $this->db->quote($longURL) . ", " . $this->db->quote($shortURL) . ", " . $this->db->quote($user) . ", " . time() . ")";
		$this->db->query($sql);

		return self::expandShortURL($shortURL);
	}

	/**
	 * Deletes an url mapping.
	 *
	 * @param integer $shortUrlID
	 * @return boolean
	 */
	public function delete($shortUrlID) {
		$sql = "DELETE FROM url_map WHERE shortUrlID = " . intval($shortUrlID);
		return (bool)$this->db->exec($sql);
	}

	/**
	 * Strips an URL.
	 *
	 * @param string $url
	 * @return string
	 */
	public function stripURL($url) {
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
	 * @param string $shortURL
	 * @return string
	 */
	public static function expandShortURL($shortURL) {
		return SERVICE_BASEURL . '?' . $shortURL;
	}
}