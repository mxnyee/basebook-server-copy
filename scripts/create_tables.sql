CREATE TABLE Country (
  state CHAR(2),
  country CHAR(2) NOT NULL,
  PRIMARY KEY (state)
);

CREATE TABLE City (
  city VARCHAR(64),
  state CHAR(2),
  PRIMARY KEY (city, state),
  FOREIGN KEY (state) REFERENCES Country(state)
    ON UPDATE CASCADE
);

CREATE TABLE Location (
  locationName VARCHAR(64),
  city VARCHAR(64),
  state CHAR(2),
  PRIMARY KEY (locationName, city, state),
  FOREIGN KEY (city, state) REFERENCES City(city, state)
    ON UPDATE CASCADE
);

CREATE TABLE Permissions (
  AccountType VARCHAR(16),
  canSeeStats BOOLEAN NOT NULL,
  canSeeLeaderboard BOOLEAN NOT NULL,
  PRIMARY KEY (AccountType)
);

CREATE TABLE Account (
  username VARCHAR(64),
  email VARCHAR(256) NOT NULL UNIQUE,
  password VARCHAR(64) NOT NULL,
  name VARCHAR(64),
  city VARCHAR(64),
  state CHAR(2),
  numCoins INT NOT NULL DEFAULT 0,
  AccountType VARCHAR(16) NOT NULL DEFAULT 'Regular',
  PRIMARY KEY (username),
  FOREIGN KEY (city, state) REFERENCES City(city, state)
    ON UPDATE CASCADE,
  FOREIGN KEY (AccountType) REFERENCES Permissions(AccountType)
    ON UPDATE CASCADE
);

CREATE TABLE AccountUpgrade (
  itemId SMALLINT AUTO_INCREMENT,
  itemName VARCHAR(16) NOT NULL UNIQUE,
  description VARCHAR(64),
  price SMALLINT NOT NULL,
  PRIMARY KEY (itemId)
);

CREATE TABLE Superpower (
  itemId SMALLINT,
  duration TINYINT NOT NULL,
  PRIMARY KEY (itemId),
  FOREIGN KEY (itemId) REFERENCES AccountUpgrade(itemId)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

CREATE TABLE Accessory (
  itemId SMALLINT,
  color CHAR(7) NOT NULL DEFAULT '#00B7EB',
  PRIMARY KEY (itemId),
  FOREIGN KEY (itemId) REFERENCES AccountUpgrade(itemId)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

CREATE TABLE purchase (
  username VARCHAR(64),
  itemId SMALLINT,
  expiryDate DATE,
  PRIMARY KEY (username, itemId),
  FOREIGN KEY (username) REFERENCES Account(username)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  FOREIGN KEY (itemId) REFERENCES AccountUpgrade(itemId)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

CREATE TABLE post (
  postId SMALLINT AUTO_INCREMENT,
  username VARCHAR(64) NOT NULL,
  title VARCHAR(64) NOT NULL,
  text VARCHAR(1024) NOT NULL,
  locationName VARCHAR(64),
  city VARCHAR(64),
  state CHAR(2),
  timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  numLikes INT NOT NULL DEFAULT 0,
  numDislikes INT NOT NULL DEFAULT 0,
  numComments INT NOT NULL DEFAULT 0,
  PRIMARY KEY (postId),
  FOREIGN KEY (username) REFERENCES Account(username)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  FOREIGN KEY (locationName, city, state) REFERENCES Location(locationName, city, state)
    ON UPDATE CASCADE,
  FOREIGN KEY (city, state) REFERENCES City(city, state)
    ON UPDATE CASCADE
);

CREATE TABLE PostReaction (
  username VARCHAR(64),
  postId SMALLINT,
  value TINYINT NOT NULL,
  PRIMARY KEY (username, postId),
  FOREIGN KEY (username) REFERENCES Account(username)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  FOREIGN KEY (postId) REFERENCES Post(postId)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

CREATE TABLE Comment (
  commentId SMALLINT AUTO_INCREMENT,
  postId SMALLINT,
  username VARCHAR(64) NOT NULL,
  text VARCHAR(1024) NOT NULL,
  timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  numLikes INT NOT NULL DEFAULT 0,
  numDislikes INT NOT NULL DEFAULT 0,
  PRIMARY KEY (commentId, postId),
  FOREIGN KEY (postId) REFERENCES Post(postId)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  FOREIGN KEY (username) REFERENCES Account(username)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

CREATE TABLE CommentReaction (
  username VARCHAR(64),
  commentId SMALLINT,
  postId SMALLINT,
  value TINYINT NOT NULL,
  PRIMARY KEY (username, commentId, postId),
  FOREIGN KEY (username) REFERENCES Account(username)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  FOREIGN KEY (commentId, postId) REFERENCES Comment(commentId, postId)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

CREATE VIEW NumPosts(username, numPosts) AS (
  SELECT username, COUNT(postId) as numPosts
  FROM Account LEFT JOIN Post USING(username)
  GROUP BY username
  ORDER BY numPosts DESC, username ASC
);

CREATE VIEW NumComments(username, numComments) AS (
  SELECT username, COUNT(commentId) as numComments
  FROM Account LEFT JOIN Comment USING(username)
  GROUP BY username
  ORDER BY numComments DESC, username ASC
);