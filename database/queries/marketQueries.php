<?php

const MARKET_QUERIES = [

  'getAllSuperpowers' => '
    SELECT itemId, itemName, description, price, duration
    FROM AccountUpgrade
    JOIN Superpower USING(itemId)
    ORDER BY itemName DESC
  ',

  'getAllAccessories' => '
    SELECT itemId, itemName, description, price
    FROM AccountUpgrade
    JOIN Accessory USING(itemId)
    ORDER BY itemName DESC
  ',

  'checkForItem' => '
    SELECT itemId
    FROM AccountUpgrade
    WHERE itemId = ?
  ',

  'insertPurchase' => '
    INSERT INTO Purchase
    VALUES(?, ?, CURRENT_DATE() + ?)
  ',

  'removeExpiredPurchases' => '
    DELETE FROM Purchase
    WHERE expiryDate < CURRENT_DATE()
  ',

  'getUserSuperpowers' => '
    SELECT itemId, itemName, description, expiryDate
    FROM Purchase
    JOIN AccountUpgrade USING(itemId)
    JOIN Superpower USING(itemId)
    WHERE username = ?
    ORDER BY itemName DESC
  ',

  'getUserAccessories' => '
    SELECT itemId, itemName, description, color
    FROM Purchase
    JOIN AccountUpgrade USING(itemId)
    JOIN Accessory USING(itemId)
    WHERE username = ?
    ORDER BY itemName DESC
  '

];