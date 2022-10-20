CREATE TABLE IF NOT EXISTS application (
  applicationID INT          NOT NULL AUTO_INCREMENT,
  domainHost    VARCHAR(255) NOT NULL DEFAULT 'localhost',
  domainPath    VARCHAR(255) NOT NULL DEFAULT '/',

  PRIMARY KEY (applicationID)
);

CREATE TABLE IF NOT EXISTS short_url (
  shortUrlID    INT           NOT NULL AUTO_INCREMENT,
  applicationID INT           NOT NULL,
  shortUrl      VARCHAR(50)   NOT NULL,
  longUrl       VARCHAR(1024) NULL DEFAULT NULL,
  secret        MEDIUMTEXT    NULL DEFAULT NULL,
  userID        INT           NULL DEFAULT NULL,
  createdTime   INT           NOT NULL,
  expire        INT           NULL DEFAULT NULL,
  details       MEDIUMTEXT    NOT NULL,
  protected     TINYINT       NOT NULL DEFAULT 0,

  PRIMARY KEY (shortUrlID),
  UNIQUE KEY (applicationID, shortUrl)
);

CREATE TABLE IF NOT EXISTS user (
  userID   INT                    NOT NULL AUTO_INCREMENT,
  username VARCHAR(255)           NOT NULL,
  password VARCHAR(255)           NOT NULL,
  role     ENUM ('admin', 'user') NOT NULL DEFAULT 'user',

  PRIMARY KEY (userID),
  UNIQUE KEY (username)
);

ALTER TABLE short_url
  ADD FOREIGN KEY (applicationID) REFERENCES application (applicationID)
    ON DELETE CASCADE;
ALTER TABLE short_url
  ADD FOREIGN KEY (userID) REFERENCES user (userID)
    ON DELETE SET NULL;
