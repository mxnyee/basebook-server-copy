<?php

const MARKET_QUERIES = [

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
    SELECT itemId, itemName, description
    FROM Purchase JOIN AccountUpgrade USING(itemId)
    WHERE username = ? AND itemId NOT IN (
      SELECT itemId
      FROM Superpower
    )
    ORDER BY itemName DESC
  '

];