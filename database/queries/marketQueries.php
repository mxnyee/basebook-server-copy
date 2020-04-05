<?php

const MARKET_QUERIES = [

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
  '

];