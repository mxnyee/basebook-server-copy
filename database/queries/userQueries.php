<?php

const USER_QUERIES = [

  'get_num_users' => '
    SELECT COUNT(username) as num_users
    FROM account a
  ',

  'insertUser' => '
    INSERT INTO account(username, email, password, name, city, state, num_coins, account_type)
    VALUES(?, ?, ?, ?, ?, ?, ?, ?)
  ',
  
  'check_for_user' => '
    SELECT username
    FROM account
    WHERE username = ?
  ',
  
  'check_user_password' => '
    SELECT username, password
    FROM account
    WHERE username = ? and password = ?
  ',

  'get_all_user_info' => '
    SELECT email, name, city, state, country, num_coins, account_type
    FROM account a LEFT JOIN country c USING(state)
    WHERE a.username = ?
  ',

  'get_user_inventory' => '
    SELECT item_id, item_name, description, expiry_date, color
    FROM purchase p
    JOIN account_upgrade au USING(item_id)
    LEFT JOIN superpower s USING(item_id)
    LEFT JOIN accessory a USING(item_id)
    WHERE p.username = ?
  ',

  'get_user_num_posts' => '
    SELECT num_posts
    FROM num_posts np
    WHERE username = ?
  ',

  'get_user_num_comments' => '
    SELECT num_comments
    FROM num_comments nc
    WHERE username = ?
  ',

  'get_user_rank_by_num_posts' => '
    SELECT COUNT(username) as user_rank_by_num_posts
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

  'get_ranking_by_num_posts' => '
    SELECT username, num_posts
    FROM num_posts np
    LIMIT ?, ?
  ',

  'get_user_rank_by_num_comments' => '
    SELECT COUNT(username) as user_rank_by_num_comments
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

  'get_ranking_by_num_comments' => '
    SELECT username, num_comments
    FROM num_comments nc
    LIMIT ?, ?
  '
  
];