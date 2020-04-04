<?php

const LOCATION_PREPARED_QUERIES = [

  'checkForCity' => '
    SELECT 1
    FROM city c
    WHERE city = ? and state = ?
  ',
  
  'insertCity' => '
    INSERT INTO city(city, state)
    VALUES(?, ?)
  ',

  'checkForLocation' => '
    SELECT 1
    FROM location l
    WHERE location_name = ? and city = ? and state = ?
  ',
  
  'insertLocation' => '
    INSERT INTO location(location_name, city, state)
    VALUES(?, ?, ?)
  ',
  
];