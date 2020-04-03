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
    $properties->{$propertyName} = (object) ["\$ref" => "definitions.json#/$propertyName"];
  }

  $schema = (object) [
    'type' => 'object',
    'properties' => $properties,
    'required' => $requiredData,
    'additionalProperties' => false,
    'dependencies' => (object) [
      'locationName' => ['city', 'state'],
      'city' => ['state']
    ]
  ];

  return $schema;
}
