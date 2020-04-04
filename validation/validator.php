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
  $method = $request->getMethod();

  $useDependencies = !($method == 'PUT' || $method == 'PATCH');
  validateParams($params, $validParams);
  validateBody($schemaStorage, $fields, $validFields, $requiredFields, $useDependencies);
}

function validateParams($data, $validData) {
  // Can't have an empty enum, so manually check $data when $validData is empty
  if (empty($validData)) {
    if (!empty($data)) {
      throw new BadRequestException('Extraneous parameters or data.');
    } else {
      return;
    }
  }

  if (is_null($data)) {
    throw new BadRequestException('Data is null.');
  }
  
  $array = array_keys($data);
  $enum = buildEnum($validData);

  $validator = new Validator();
  $validator->validate($array, $enum);
  
  if (!$validator->isValid()) {
    $error = $validator->getErrors()[0];
    $err = ' ["' . $array[$error['property'][1]] . '"] ' . $error['message'];
    throw new BadRequestException($err);
  }
}

function validateBody($schemaStorage, $data, $validData, $requiredData, $useDependencies) {
  $object = (object)$data;
  $schema = buildSchema($validData, $requiredData, $useDependencies);

  // The path to validation/definitions.json relative to index.php
  $schemaStorage->addSchema('../validation/definitions.json', $schema);
  $validator = new Validator( new Factory($schemaStorage) );

  $validator->coerce($object, $schema);
  
  if (!$validator->isValid()) {
    $error = $validator->getErrors()[0];
    $err = ' ["' . $error['property'] . '"] ' . $error['message'];
    throw new BadRequestException($err);
  }
}
