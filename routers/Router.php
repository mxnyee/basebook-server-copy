<?php
use Psr\Container\ContainerInterface;

foreach (glob('../controllers/*.php') as $filename) { require_once $filename; }
foreach (glob('../errorHandling/*.php') as $filename) { require_once $filename; }

class Router {
  protected $validator;
  protected $controller;

  public function __construct(ContainerInterface $container, Controller $controller) {
    $this->validator = $container->get('validator');
    $this->controller = $controller;
  }

}