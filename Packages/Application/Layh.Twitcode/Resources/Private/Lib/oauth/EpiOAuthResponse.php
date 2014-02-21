<?php

class EpiOAuthResponse
{
  private $__resp;
  protected $debug = false;

  public function __construct($resp)
  {
    $this->__resp = $resp;
  }

  public function __get($name)
  {
    if($this->__resp->code != 200)
      EpiOAuthException::raise($this->__resp, $this->debug);

    parse_str($this->__resp->data, $result);
    foreach($result as $k => $v)
    {
      $this->$k = $v;
    }

    return isset($result[$name]) ? $result[$name] : null;
  }

  public function __toString()
  {
    return $this->__resp->data;
  }
}
