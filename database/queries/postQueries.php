<?php

const POST_QUERIES = [

  'insertPost' => '
    INSERT INTO Post(username, title, text, locationName, city, state)
    VALUES(?, ?, ?, ?, ?, ?)
  ',
  
];