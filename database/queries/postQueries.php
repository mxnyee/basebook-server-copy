<?php

const POST_QUERIES = [

  'insertPost' => '
    INSERT INTO Post(username, title, text, locationName, city, state)
    VALUES(?, ?, ?, ?, ?, ?)
  ',
  
  'addUserReactionToPost' => '
    INSERT INTO PostReaction(username, postId, value)
    VALUES(?, ?, ?)
    ON DUPLICATE KEY UPDATE value = ?
  ',

  'checkForUserReactionToPost' => '
    SELECT value
    FROM PostReaction
    WHERE username = ? AND postId = ?
  ',

  'removeUserReactionToPost' => '
    DELETE FROM PostReaction
    WHERE username = ? AND postId = ?
  '
  
];