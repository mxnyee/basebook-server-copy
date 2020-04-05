<?php

foreach (glob(__DIR__ . '/*.php') as $filename) { require_once $filename; }

function handleThrown($response, $error) {
  switch (get_class($error)) {
    case 'BadRequestException': return handleBadRequest($response, $error->getMessage());
    case 'ForbiddenException': return handleForbidden($response, $error->getMessage());
    case 'NotFoundException': return handleNotFound($response, $error->getMessage());
    case 'InternalServerErrorException': return handleInternalServerError($response, $error->getMessage());
    default: throw $error;
  }
}