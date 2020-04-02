<?php
use JsonSchema\SchemaStorage;
use JsonSchema\Validator;
use JsonSchema\Constraints\Factory;

require_once 'schemaBuilder.php';

function createValidator() {
  $schemaStorage = new SchemaStorage();
  return $schemaStorage;
}

function validate($schemaStorage, $request, $validParams, $validFields, $requiredFields) {
  $params = $request->getQueryParams();
  $fields = $request->getParsedBody();

  checkExistence($params, $validParams);
  checkExistence($fields, $validFields);
  checkValues($schemaStorage, $fields, $validFields, $requiredFields);
}

function checkExistence($data, $validData) {
  // Can't have an empty enum, so manually check $data when $validData is empty
  if (empty($validData)) {
    if (!empty($data)) {
      throw new Exception("Invalid request: Extraneous parameters or data.");
    } else {
      return;
    }
  }

  if (is_null($data)) {
    throw new Exception("Invalid request: Data is null.");
  }
  
  $array = array_keys($data);
  $enum = buildEnum($validData);

  $validator = new Validator();
  $validator->validate($array, $enum);
  
  if (!$validator->isValid()) {
    $err = '';
    foreach ($validator->getErrors() as $error) {
      $err .= sprintf("  [\"%s\"] %s\n", $array[$error['property'][1]], $error['message']);
    }
    throw new Exception($err);
  }
}

function checkValues($schemaStorage, $data, $validData, $requiredData) {
  $object = (object)$data;
  $schema = buildSchema($validData, $requiredData);

  // The path to definitions.json relative to index.php
  $schemaStorage->addSchema('../validation/definitions.json', $schema);
  $validator = new Validator( new Factory($schemaStorage) );

  $validator->coerce($object, $schema);
  
  if (!$validator->isValid()) {
    $err = '';
    foreach ($validator->getErrors() as $error) {
      $err .= sprintf("[\"%s\"] %s", $error['property'], $error['message']);
    }
    throw new Exception($err);
  }
}
