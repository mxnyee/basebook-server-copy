<?php

function buildEnum($validData) {
  $enum = (object) [ 
    'type' => 'array',
    'items' => (object) [
      'enum' => $validData 
    ]
  ];
  return $enum;
}

function buildSchema($validData, $requiredData) {
  // Add properties to the schema by referencing an external file
  $properties = new StdClass();
  foreach ($validData as $propertyName) {
    $properties->{$propertyName} = (object) ["\$ref" => "#/$propertyName"];
  }

  $schema = (object) [
    'type' => 'object',
    'properties' => $properties,
    'required' => $requiredData
  ];

  return $schema;
}