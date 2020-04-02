<?php

function buildSchema($fields, $required) {
  $properties = new StdClass();
  foreach ($fields as $fieldName) {
    $properties->{$fieldName} = "{ \"\$ref\": \"./definitions.json#/$fieldName\" }";
  }

  $schema = (object) [
    'type' => 'object',
    'properties' => $properties,
    'required' => $required
  ];

  return $schema;
}