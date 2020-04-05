<?php

foreach (glob(__DIR__ . '/*.php') as $filename) { require_once $filename; }

function handleThrown($response, $error) {
  switch (get_class($error)) {
    case 'BadRequestException': return handleBadRequest($response, $error->getMessage());
    case 'ForbiddenException': return handleForbidden($response, $error->getMessage());
    case 'NotFoundException': return handleNotFound($response, $error->getMessage());
    case 'mysqli_sql_exception': 
      if ($error->getCode() == 1062) return handleInternalServerError($response, 'Duplicate entry.');
    default: throw $error;
  }
}