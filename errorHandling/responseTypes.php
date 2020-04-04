<?php

// Remove null values and normalize
function clean(&$data) {
  if (!is_iterable($data)) return;
  $isObject = is_object($data);
  if ($isObject) $data = (array) $data;
  $data = array_filter($data, function($v) { return !is_null($v); });

  foreach ($data as $key => &$innerData) {
    clean($innerData);
  }

  if ($isObject) $data = (object) $data;
  return $data;
}

// Format JSON
function json($response, $data) {
  clean($data);
  $response->getBody()->write(json_encode($data));
  return $response
    ->withHeader('Content-Type', 'application/json');
}


// Common responses

function handleSuccess($response, $data) {
  return json($response, $data);
}

function handleError($response, $message) {
  $errorMessage = (object) ['error' => true, 'message' => $message];
  return json($response, $errorMessage);
}


// Successes

function responseOk($response, $data) {
  return handleSuccess($response, $data)
    ->withStatus(200);
}

function responseCreated($response, $data) {
  return handleSuccess($response, $data)
    ->withStatus(201);
}

function responseNoContent($response) {
  return $response
    ->withStatus(204);
}


// Errors

function handleBadRequest($response, $message) {
  return handleError($response, $message)
    ->withStatus(400);
}

function handleNotFound($response, $message) {
  return handleError($response, $message)
    ->withStatus(404);
}

function handleInternalServerError($response, $message) {
  return handleError($response, $message)
    ->withStatus(500);
}