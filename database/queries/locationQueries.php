<?php

const LOCATION_QUERIES = [

  'checkForCity' => '
    SELECT city, state
    FROM City
    WHERE city = ? and state = ?
  ',
  
  'insertCity' => '
    INSERT INTO City
    VALUES(?, ?)
  ',

  'checkForLocation' => '
    SELECT locationName, city, state
    FROM Location
    WHERE locationName = ? and city = ? and state = ?
  ',
  
  'insertLocation' => '
    INSERT INTO Location
    VALUES(?, ?, ?)
  ',
  
];