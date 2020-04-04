<?php

const LOCATION_QUERIES = [

  'check_for_city' => '
    SELECT 1
    FROM city c
    WHERE city = ? and state = ?
  ',
  
  'insertCity' => '
    INSERT INTO city(city, state)
    VALUES(?, ?)
  ',

  'check_for_location' => '
    SELECT 1
    FROM location l
    WHERE location_name = ? and city = ? and state = ?
  ',
  
  'insert_location' => '
    INSERT INTO location(location_name, city, state)
    VALUES(?, ?, ?)
  ',
  
];