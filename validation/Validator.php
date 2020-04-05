<?php
use JsonSchema\SchemaStorage;
use JsonSchema\Validator as JsonValidator;
use JsonSchema\Constraints\Factory;
use JsonSchema\Constraints\Constraint;

require_once 'schemaBuilder.php';

class Validator {
  private $schemaStorage;
  private $paramValidator;

  public function __construct() {
    $this->schemaStorage = new SchemaStorage();
    $this->paramValidator = new JsonValidator();
  }
  

  public function validate($params, &$body, $validParams, $validFields, $requiredFields, $useDependencies) {
    $this->validateParams($params, $validParams);
    $this->validateBody($body, $validFields, $requiredFields, $useDependencies);
    $this->fillMissingData($body, $validFields);
  }


  private function validateParams($data, $validData) {
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

    $this->paramValidator->validate($array, $enum);
    
    if (!$this->paramValidator->isValid()) {
      $error = $this->paramValidator->getErrors()[0];
      $err = ' [' . $array[$error['property'][1]] . '] ' . $error['message'];
      throw new BadRequestException($err);
    }
  }


  private function validateBody($data, $validData, $requiredData, $useDependencies) {
    $object = (object)$data;
    $schema = buildSchema($validData, $requiredData, $useDependencies);

    // The path to validation/definitions.json relative to index.php
    $this->schemaStorage->addSchema('../validation/definitions.json', $schema);
    $bodyValidator = new JsonValidator( new Factory($this->schemaStorage) );

    $bodyValidator->coerce($object, $schema);
    
    if (!$bodyValidator->isValid()) {
      $error = $bodyValidator->getErrors()[0];
      $err = ' [' . $error['property'] . '] ' . $error['message'];
      throw new BadRequestException($err);
    }
  }


  private function fillMissingData(&$data, $validData) {
    foreach($validData as $key) {
      if (!array_key_exists($key, $data)) {
        $data[$key] = NULL;
      }
    }
  }

}
