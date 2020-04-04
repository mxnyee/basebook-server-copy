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

function buildSchema($validData, $requiredData, $useDependencies) {
  // Add properties to the schema by referencing an external file
  $properties = (object) [];
  foreach ($validData as $propertyName) {
    $properties->{$propertyName} = (object) ['$ref' => '#/' . $propertyName];
  }

  $schema = (object) [
    'type' => 'object',
    'properties' => $properties,
    'required' => $requiredData,
    'additionalProperties' => false
  ];

  // Property dependencies
  if ($useDependencies) {
    $schema->{'dependencies'} = (object) [
        'locationName' => ['city', 'state'],
        'city' => ['state']
    ];
  }

  return $schema;
}
