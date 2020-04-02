<?php

class InternalServerErrorException extends Exception {
  public function getMsg() {
    $msg = 'Internal Server Error: ' . $this->getMessage();
    return $msg;
  }
}