<?php

// Common responses

function json($response, $data) {
  // Remove null values (2 nested levels deep)
  foreach ($data as $key => &$value) {
    if (is_null($value)) unset($data[$key]);
    else if (is_iterable($value)) {
      foreach ($value as $innerKey => $innerValue) {
        if (is_null($innerValue)) unset($value[$innerKey]);
      }
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
  $errorMessage = ['error' => true, 'message' => $message];
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