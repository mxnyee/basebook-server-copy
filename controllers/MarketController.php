<?php
use Psr\Container\ContainerInterface;

class MarketController {
  protected $container;

  public function __construct(ContainerInterface $container) {
    $this->container = $container;
  }

  public function getAllItems($request, $response, $args) {
    return $response;
  }

  public function purchaseItem($request, $response, $args) {
    return $response;
  }
}