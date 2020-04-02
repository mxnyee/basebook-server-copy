<?php

function buildSchema($fields, $required) {
  // Add properties to the schema by referencing an external file
  $properties = new StdClass();
  foreach ($fields as $fieldName) {
    $properties->{$fieldName} = (object) ["\$ref" => "#/$fieldName"];
  }

  $schema = (object) [
    'type' => 'object',
    'properties' => $properties,
    'required' => $required
  ];

  return $schema;
}