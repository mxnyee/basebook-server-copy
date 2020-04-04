<?php

const USER_QUERIES = [

  'getNumUsers' => '
    SELECT COUNT(username) as numUsers
    FROM Account
  ',

  'insertUser' => '
    INSERT INTO Account(username, email, password, name, city, state, accountType)
    VALUES(?, ?, ?, ?, ?, ?, ?)
  ',
  
  'checkForUser' => '
    SELECT username
    FROM Account
    WHERE username = ?
  ',
  
  'checkUserPassword' => '
    SELECT username, password
    FROM Account
    WHERE username = ? and password = ?
  ',

  'getAllUserInfo' => '
    SELECT email, name, city, state, country, numCoins, accountType
    FROM Account LEFT JOIN Country USING(state)
    WHERE username = ?
  ',

  'getUserSuperpowers' => '
    SELECT itemId, itemName, description, expiryDate
    FROM Purchase
    JOIN AccountUpgrade USING(itemId)
    LEFT JOIN Superpower USING(itemId)
    WHERE username = ?
  ',

  'getUserAccessories' => '
    SELECT itemId, itemName, description, color
    FROM Purchase
    JOIN AccountUpgrade USING(itemId)
    LEFT JOIN Accessory USING(itemId)
    WHERE username = ?
  ',

  'getUserNumPosts' => '
    SELECT numPosts
    FROM NumPostsByUser
    WHERE username = ?
  ',

  'getUserNumComments' => '
    SELECT numComments
    FROM NumCommentsByUser
    WHERE username = ?
  ',

  'getUserRankByNumPosts' => '
    SELECT COUNT(username) as userRankByNumPostsByUser
    FROM NumPostsByUser
    WHERE numPosts > ALL (
        SELECT numPosts FROM NumPostsByUser WHERE username = ?
      )
      or (username <= ?
        and numPosts = ALL (
            SELECT numPosts FROM NumPostsByUser WHERE username = ?
          )
      )
  ',

  'getRankingByNumPosts' => '
    SELECT username, numPosts
    FROM NumPostsByUser
    LIMIT ?, ?
  ',

  'getUserRankByNumComments' => '
    SELECT COUNT(username) as userRankByNumCommentsByUser
    FROM NumCommentsByUser
    WHERE numComments > ALL (
        SELECT numComments FROM NumCommentsByUser WHERE username = ?
      )
      or (username <= ?
        and numComments = ALL (
            SELECT numComments FROM NumCommentsByUser WHERE username = ?
          )
      )
  ',

  'getRankingByNumComments' => '
    SELECT username, numComments
    FROM NumCommentsByUser
    LIMIT ?, ?
  '
  
];