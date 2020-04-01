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
  can_delete_posts BOOLEAN NOT NULL,
  can_delete_comments BOOLEAN NOT NULL,
  can_ban_users BOOLEAN NOT NULL,
  PRIMARY KEY (account_type)
);

CREATE TABLE account ( 
  user_id CHAR(16),
  token CHAR(16) NOT NULL UNIQUE,
  username VARCHAR(64) NOT NULL UNIQUE,
  email VARCHAR(256) NOT NULL UNIQUE,
  password VARCHAR(64) NOT NULL,
  num_coins INT NOT NULL DEFAULT 0,
  account_type VARCHAR(16) NOT NULL DEFAULT 'personal',
  profile_page_url VARCHAR(256) UNIQUE,
  PRIMARY KEY (user_id),
  FOREIGN KEY (account_type) REFERENCES permissions(account_type)
    ON UPDATE CASCADE
);

CREATE TABLE profile_page (
  profile_page_url VARCHAR(256),
  user_id CHAR(16) NOT NULL UNIQUE,
  profile_picture_url VARCHAR(256),
  name VARCHAR(64),
  biography VARCHAR(1024),
  city VARCHAR(64),
  state CHAR(2),
  num_posts INT NOT NULL DEFAULT 0,
  num_followers INT NOT NULL DEFAULT 0, 
  num_following INT NOT NULL DEFAULT 0,
  PRIMARY KEY (profile_page_url),
  FOREIGN KEY (city, state) REFERENCES city(city, state)
    ON UPDATE CASCADE,
  FOREIGN KEY (user_id) REFERENCES account(user_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

ALTER TABLE account
ADD FOREIGN KEY (profile_page_url) REFERENCES profile_page(profile_page_url)
  ON UPDATE CASCADE
;

CREATE TABLE follow (
  follower CHAR(16),
  following CHAR(16),
  PRIMARY KEY (follower, following),
  FOREIGN KEY (follower) REFERENCES account(user_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  FOREIGN KEY (following) REFERENCES account(user_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

CREATE TABLE account_upgrade (
  item_id CHAR(16),
  item_name VARCHAR(16) NOT NULL UNIQUE,
  price INT NOT NULL,
  PRIMARY KEY (item_id)
);

CREATE TABLE superpower (
  item_id CHAR(16),
  duration INT NOT NULL,
  PRIMARY KEY (item_id),
  FOREIGN KEY (item_id) REFERENCES account_upgrade(item_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

CREATE TABLE accessory (
  item_id CHAR(16),
  color CHAR(7) NOT NULL DEFAULT '#00B7EB',
  PRIMARY KEY (item_id),
  FOREIGN KEY (item_id) REFERENCES account_upgrade(item_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

CREATE TABLE purchase (
  user_id CHAR(16)
  item_id CHAR(16),
  amount INT NOT NULL DEFAULT 1,
  expiry_date DATE,
  PRIMARY KEY (user_id, item_id),
  FOREIGN KEY (user_id) REFERENCES account(user_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  FOREIGN KEY (item_id) REFERENCES account_upgrade(item_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

CREATE TABLE hashtag (
  hashtag VARCHAR(64),
  PRIMARY KEY (hashtag)
);

CREATE TABLE post (
  post_id CHAR(16),
  user_id CHAR(16) NOT NULL,
  title VARCHAR(64) NOT NULL,
  timestamp TIMESTAMP NOT NULL,
  num_likes INT NOT NULL DEFAULT 0,
  num_dislikes INT NOT NULL DEFAULT 0,
  num_comments INT NOT NULL DEFAULT 0,
  location_name VARCHAR(64),
  city VARCHAR(64),
  state CHAR(2),
  PRIMARY KEY (post_id),
  FOREIGN KEY (user_id) REFERENCES account(user_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  FOREIGN KEY (location_name, city, state) REFERENCES location(location_name, city, state)
    ON UPDATE CASCADE,
  FOREIGN KEY (city, state) REFERENCES city(city, state)
    ON UPDATE CASCADE
);

CREATE TABLE text_post (
  post_id CHAR(16),
  text VARCHAR(1024) NOT NULL,
  PRIMARY KEY (post_id),
  FOREIGN KEY (post_id) REFERENCES post(post_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

CREATE TABLE photo_post (
  post_id CHAR(16),
  photo_url VARCHAR(256) NOT NULL,
  PRIMARY KEY (post_id),
  FOREIGN KEY (post_id) REFERENCES post(post_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

CREATE TABLE video_post (
  post_id CHAR(16),
  video_url VARCHAR(256) NOT NULL,
  PRIMARY KEY (post_id),
  FOREIGN KEY (post_id) REFERENCES post(post_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

CREATE TABLE post_hashtag (
  post_id CHAR(16),
  hashtag VARCHAR(64),
  PRIMARY KEY (post_id, hashtag),
  FOREIGN KEY (post_id) REFERENCES post(post_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  FOREIGN KEY (hashtag) REFERENCES hashtag(hashtag)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

CREATE TABLE post_reaction (
  user_id CHAR(16)
  post_id CHAR(16),
  value INT NOT NULL,
  timestamp TIMESTAMP NOT NULL,
  PRIMARY KEY (user_id, post_id),
  FOREIGN KEY (post_id) REFERENCES post(post_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES account(user_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

CREATE TABLE comment (
  comment_id CHAR(4),
  post_id CHAR(16),
  user_id CHAR(16) NOT NULL,
  timestamp TIMESTAMP NOT NULL,
  text VARCHAR(1024) NOT NULL,
  num_likes INT NOT NULL DEFAULT 0,
  num_dislikes INT NOT NULL DEFAULT 0,
  PRIMARY KEY (comment_id, post_id),
  FOREIGN KEY (post_id) REFERENCES post(post_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES account(user_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

CREATE TABLE comment_reaction (
  user_id CHAR(16)
  comment_id CHAR(4),
  post_id CHAR(16),
  value INT NOT NULL,
  timestamp TIMESTAMP NOT NULL,
  PRIMARY KEY (user_id, comment_id, post_id),
  FOREIGN KEY (user_id) REFERENCES account(user_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  FOREIGN KEY (comment_id, post_id) REFERENCES comment(comment_id, post_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);