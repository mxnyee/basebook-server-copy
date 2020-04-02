<?php
use Psr\Container\ContainerInterface;

class CommentController {
  protected $container;

  public function __construct(ContainerInterface $container) {
    $this->container = $container;
  }

  public function createComment($request, $response, $args) {
    return $response;
  }

  public function getAllCommentsOnPost($request, $response, $args) {
    return $response;
  }

  public function addCommentReaction($request, $response, $args) {
    return $response;
  }

  public function removeCommentReaction($request, $response, $args) {
    return $response;
  }
}