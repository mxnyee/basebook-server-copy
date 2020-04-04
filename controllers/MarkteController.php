<?php
use Psr\Container\ContainerInterface;
require_once 'Controller.php';

class MarketController extends Controller {
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