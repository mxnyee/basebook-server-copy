<?php
use JsonSchema\SchemaStorage;
use JsonSchema\Validator;
use JsonSchema\Constraints\Factory;

require_once 'schemaBuilder.php';

function createValidator() {
  $schemaStorage = new SchemaStorage();
  return $schemaStorage;
}

function validate($schemaStorage, $request, $fields, $required) {
  $object = (object)$request;
  $schema = buildSchema($fields, $required);

  // The path to definitions.json relative to index.php
  $schemaStorage->addSchema('../schemas/definitions.json', $schema);
  $validator = new Validator( new Factory($schemaStorage) );

  $validator->coerce($object, $schema);

  if (!$validator->isValid()) {
    $err = 'Invalid request:' . PHP_EOL;
    foreach ($validator->getErrors() as $error) {
      $err .= sprintf("  [%s] %s\n", $error['property'], $error['message']);
    }
    throw new Exception($err);
  }
}
