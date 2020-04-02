<?php

class BadRequestException extends Exception {
  public function getMsg() {
    $msg = 'Bad Request: ' . $this->getMessage();
    return $msg;
  }
}