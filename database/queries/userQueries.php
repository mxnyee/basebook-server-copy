<?php

const USER_QUERIES = [

  'getNumUsers' => '
    SELECT COUNT(username) AS numUsers
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
    WHERE username = ? AND password = ?
  ',

  'getAllUserInfo' => '
    SELECT email, name, city, state, country, numCoins, accountType
    FROM Account LEFT JOIN Country USING(state)
    WHERE username = ?
  ',

  'getUserRankByNumPosts' => '
    SELECT COUNT(username) AS userRankByNumPosts
    FROM NumPostsByUser
    WHERE numPosts > ALL (
        SELECT numPosts FROM NumPostsByUser WHERE username = ?
      )
      OR (username <= ?
        AND numPosts = ALL (
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
    SELECT COUNT(username) AS userRankByNumComments
    FROM NumCommentsByUser
    WHERE numComments > ALL (
        SELECT numComments FROM NumCommentsByUser WHERE username = ?
      )
      OR (username <= ?
        AND numComments = ALL (
            SELECT numComments FROM NumCommentsByUser WHERE username = ?
          )
      )
  ',

  'getRankingByNumComments' => '
    SELECT username, numComments
    FROM NumCommentsByUser
    LIMIT ?, ?
  ',

  'checkUserPermissions' => '
    SELECT *
    FROM Account JOIN Permissions USING(accountType)
    WHERE username = ?
  '
  
];