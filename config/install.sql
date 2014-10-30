CREATE TABLE IF NOT EXISTS application (
  applicationID INT          NOT NULL AUTO_INCREMENT,
  domainName    VARCHAR(255) NOT NULL DEFAULT 'localhost',
  domainPath    VARCHAR(255) NOT NULL DEFAULT '/',

  PRIMARY KEY (applicationID)
);

CREATE TABLE IF NOT EXISTS url_map (
  shortUrlID    INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  applicationID INT              NOT NULL,
  shortUrl      VARCHAR(50)      NOT NULL,
  longUrl       VARCHAR(1024)    NOT NULL,
  creator       VARCHAR(50)      NOT NULL,
  createdTime   INT(15) UNSIGNED NOT NULL,

  PRIMARY KEY (shortUrlID),
  UNIQUE INDEX (applicationID, shortUrl)
);

ALTER TABLE url_map ADD FOREIGN KEY (applicationID) REFERENCES application (applicationID)
  ON DELETE CASCADE;