CREATE TABLE country (
  state CHAR(2),
  country CHAR(2) NOT NULL,
  PRIMARY KEY (state)
);

CREATE TABLE city (
  city VARCHAR(64),
  state CHAR(2),
  PRIMARY KEY (city, state),
  FOREIGN KEY (state) REFERENCES country(state)
    ON UPDATE CASCADE
);

CREATE TABLE location (
  location_name VARCHAR(64),
  city VARCHAR(64),
  state CHAR(2),
  PRIMARY KEY (location_name, city, state),
  FOREIGN KEY (city, state) REFERENCES city(city, state)
    ON UPDATE CASCADE
);

CREATE TABLE permissions (
  account_type VARCHAR(16),
  can_see_stats BOOLEAN NOT NULL,
  can_see_leaderboard BOOLEAN NOT NULL,
  PRIMARY KEY (account_type)
);

CREATE TABLE account (
  username VARCHAR(64),
  email VARCHAR(256) NOT NULL UNIQUE,
  password VARCHAR(64) NOT NULL,
  name VARCHAR(64),
  city VARCHAR(64),
  state CHAR(2),
  num_coins INT NOT NULL DEFAULT 0,
  account_type VARCHAR(16) NOT NULL DEFAULT 'Regular',
  PRIMARY KEY (username),
  FOREIGN KEY (city, state) REFERENCES city(city, state)
    ON UPDATE CASCADE,
  FOREIGN KEY (account_type) REFERENCES permissions(account_type)
    ON UPDATE CASCADE
);

CREATE TABLE account_upgrade (
  item_id SMALLINT AUTO_INCREMENT,
  item_name VARCHAR(16) NOT NULL UNIQUE,
  description VARCHAR(64),
  price SMALLINT NOT NULL,
  PRIMARY KEY (item_id)
);

CREATE TABLE superpower (
  item_id SMALLINT,
  duration TINYINT NOT NULL,
  PRIMARY KEY (item_id),
  FOREIGN KEY (item_id) REFERENCES account_upgrade(item_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

CREATE TABLE accessory (
  item_id SMALLINT,
  color CHAR(7) NOT NULL DEFAULT '#00B7EB',
  PRIMARY KEY (item_id),
  FOREIGN KEY (item_id) REFERENCES account_upgrade(item_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

CREATE TABLE purchase (
  username VARCHAR(64),
  item_id SMALLINT,
  expiry_date DATE,
  PRIMARY KEY (username, item_id),
  FOREIGN KEY (username) REFERENCES account(username)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  FOREIGN KEY (item_id) REFERENCES account_upgrade(item_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

CREATE TABLE post (
  post_id SMALLINT AUTO_INCREMENT,
  username VARCHAR(64) NOT NULL,
  title VARCHAR(64) NOT NULL,
  text VARCHAR(1024) NOT NULL,
  location_name VARCHAR(64),
  city VARCHAR(64),
  state CHAR(2),
  timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  num_likes INT NOT NULL DEFAULT 0,
  num_dislikes INT NOT NULL DEFAULT 0,
  num_comments INT NOT NULL DEFAULT 0,
  PRIMARY KEY (post_id),
  FOREIGN KEY (username) REFERENCES account(username)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  FOREIGN KEY (location_name, city, state) REFERENCES location(location_name, city, state)
    ON UPDATE CASCADE,
  FOREIGN KEY (city, state) REFERENCES city(city, state)
    ON UPDATE CASCADE
);

CREATE TABLE post_reaction (
  username VARCHAR(64),
  post_id SMALLINT,
  value TINYINT NOT NULL,
  PRIMARY KEY (username, post_id),
  FOREIGN KEY (username) REFERENCES account(username)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  FOREIGN KEY (post_id) REFERENCES post(post_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

CREATE TABLE comment (
  comment_id SMALLINT AUTO_INCREMENT,
  post_id SMALLINT,
  username VARCHAR(64) NOT NULL,
  text VARCHAR(1024) NOT NULL,
  timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  num_likes INT NOT NULL DEFAULT 0,
  num_dislikes INT NOT NULL DEFAULT 0,
  PRIMARY KEY (comment_id, post_id),
  FOREIGN KEY (post_id) REFERENCES post(post_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  FOREIGN KEY (username) REFERENCES account(username)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

CREATE TABLE comment_reaction (
  username VARCHAR(64),
  comment_id SMALLINT,
  post_id SMALLINT,
  value TINYINT NOT NULL,
  PRIMARY KEY (username, comment_id, post_id),
  FOREIGN KEY (username) REFERENCES account(username)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  FOREIGN KEY (comment_id, post_id) REFERENCES comment(comment_id, post_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

CREATE VIEW num_posts(username, num_posts) AS
  SELECT username, COUNT(post_id) as num_posts
  FROM account a LEFT JOIN post p USING(username)
  GROUP BY username
  ORDER BY num_posts DESC, username ASC

CREATE VIEW num_comments(username, num_comments) AS
  SELECT username, COUNT(comment_id) as num_comments
  FROM account a LEFT JOIN comment c USING(username)
  GROUP BY username
  ORDER BY num_comments DESC, username ASC