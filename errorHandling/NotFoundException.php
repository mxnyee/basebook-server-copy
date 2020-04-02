<?php

class NotFoundException extends Exception {
  public function getMsg() {
    $msg = 'Not Found: ' . $this->getMessage();
    return $msg;
  }
}