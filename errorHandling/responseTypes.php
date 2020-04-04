<?php

// Common responses

function json($response, $data) {
  // Remove null values (2 nested levels deep)
  if (is_iterable($data)) {
    $data = (object) array_filter((array) $data);
    foreach ($data as $key => &$value)
      if (is_iterable($value)) {
        $value = (object) array_filter((array) $value);
      }
  }
  
  $response->getBody()->write(json_encode($data));
  return $response
    ->withHeader('Content-Type', 'application/json');
}

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