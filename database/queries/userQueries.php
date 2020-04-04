<?php

const USER_QUERIES = [

  'getNumUsers' => '
    SELECT COUNT(username) as numUsers
    FROM Account
  ',

  'insertUser' => '
    INSERT INTO Account
    VALUES(?, ?, ?, ?, ?, ?, ?, ?)
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

  'getUserInventory' => '
    SELECT itemId, itemName, description, expiryDate, color
    FROM Purchase
    JOIN AccountUpgrade USING(itemId)
    LEFT JOIN Superpower USING(itemId)
    LEFT JOIN Accessory USING(itemId)
    WHERE username = ?
  ',

  'getUserNumPosts' => '
    SELECT numPosts
    FROM NumPosts
    WHERE username = ?
  ',

  'getUserNumComments' => '
    SELECT numComments
    FROM NumComments
    WHERE username = ?
  ',

  'getUserRankByNumPosts' => '
    SELECT COUNT(username) as userRankByNumPosts
    FROM NumPosts
    WHERE numPosts > ALL (
        SELECT numPosts FROM numPosts WHERE username = ?
      )
      or (username <= ?
        and numPosts = ALL (
            SELECT numPosts FROM NumPosts WHERE username = ?
          )
      )
  ',

  'getRankingByNumPosts' => '
    SELECT username, numPosts
    FROM NumPosts
    LIMIT ?, ?
  ',

  'getUserRankByNumComments' => '
    SELECT COUNT(username) as userRankByNumComments
    FROM NumComments
    WHERE numComments > ALL (
        SELECT numComments FROM NumComments WHERE username = ?
      )
      or (username <= ?
        and numComments = ALL (
            SELECT numComments FROM NumComments WHERE username = ?
          )
      )
  ',

  'getRankingByNumComments' => '
    SELECT username, numComments
    FROM NumComments
    LIMIT ?, ?
  '
  
];