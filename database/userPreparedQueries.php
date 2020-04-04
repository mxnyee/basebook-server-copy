<?php

const USER_PREPARED_QUERIES = [

  'getNumUsers' => '
    SELECT 1
    FROM account a
  ',

  'insertUser' => '
    INSERT INTO account(username, email, password, name, city, state, num_coins, account_type)
    VALUES(?, ?, ?, ?, ?, ?, ?, ?)
  ',
  
  'checkForUser' => '
    SELECT 1
    FROM account
    WHERE username = ?
  ',
  
  'checkForUserWithPassword' => '
    SELECT 1
    FROM account
    WHERE username = ? and password = ?
  ',

  'getAllUserInfo' => '
    SELECT email, name, city, state, country, num_coins, account_type
    FROM account a LEFT JOIN country c USING(state)
    WHERE a.username = ?
  ',

  'getUserProperty' => '
    SELECT ?
    FROM account a LEFT JOIN country c USING(state)
    WHERE a.username = ?
  ',

  'getUserInventory' => '
    SELECT item_id, item_name, description, expiry_date, color
    FROM purchase p
    JOIN account_upgrade au USING(item_id)
    LEFT JOIN superpower s USING(item_id)
    LEFT JOIN accessory a USING(item_id)
    WHERE p.username = ?
  ',

  'getUserNumPosts' => '
    SELECT num_posts
    FROM num_posts np
    WHERE username = ?
  ',

  'getLeaderboardAboveUserByNumPosts' => '
    SELECT username, num_posts
    FROM num_posts np
    WHERE num_posts > ALL (
        SELECT num_posts FROM num_posts WHERE username = ?
      )
      or (username <= ?
        and num_posts = ALL (
            SELECT num_posts FROM num_posts WHERE username = ?
          )
      )
  ',

  'getLeaderboardAtUserByNumPosts' => '
    SELECT username, num_posts
    FROM num_posts np
    LIMIT ?, ?
  ',

  'getUserNumComments' => '
    SELECT num_comments
    FROM num_comments nc
    WHERE username = ?
  ',

  'getLeaderboardAboveUserByNumComments' => '
    SELECT username, num_comments
    FROM num_comments nc
    WHERE num_comments > ALL (
        SELECT num_comments FROM num_comments WHERE username = ?
      )
      or (username <= ?
        and num_comments = ALL (
            SELECT num_comments FROM num_comments WHERE username = ?
          )
      )
  ',

  'getLeaderboardAtUserByNumComments' => '
    SELECT username, num_comments
    FROM num_comments nc
    LIMIT ?, ?
  '
  
];