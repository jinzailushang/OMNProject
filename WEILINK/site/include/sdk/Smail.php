<?php

/**
 * Smail sdk
 *
 * @property string $serverUrl
 * @property string $appKey
 * @property string $secret
 */
class Smail {

  private $serverUrl,
    $appKey,
    $secret;

  public function __construct($serverUrl = NULL, $appKey = NULL, $secret = NULL)
  {
    if ($serverUrl) {
      $this->serverUrl = $serverUrl;
    }
    if ($appKey) {
      $this->appKey = $appKey;
    }
    if ($secret) {
      $this->secret = $secret;
    }
  }

  public function __set($name, $value)
  {
    if (property_exists($this, $name)) {
      $this->$name = $value;
    }
  }

  public function send($receiveAddress, $title, $content, $outerId = NULL, $isMarket = FALSE, $specifiedTime = 0)
  {
    $params = array(
      'action' => __FUNCTION__,
      'receiveAddress' => $receiveAddress,
      'title' => $title,
      'content' => $content,
      'outerId' => $outerId,
      'isMarket' => $isMarket,
      'specifiedTime' => $specifiedTime
    );
    return $this->post($params);
  }

  private function restObj()
  {
    if (!class_exists('restclient')) {
      require_once __DIR__ . '/restclient.php';
    }
    return new \restclient;
  }

  private function genRequestToken($time = 0)
  {
    return md5($this->appKey.'#' . ($time ? $time : time()) .'#'. $this->secret);
  }

  private function post($params)
  {
    $rest = $this->restObj();
    $rest->url = $this->serverUrl;
    $time = time();
    $rest->params = array_merge($params, array(
      'time' => $time,
      'app_key' => $this->appKey,
      'token' => $this->genRequestToken($time)
      )
    );

    $rest->post();
    return $rest->httpcode!=200?$rest:json_decode($rest->response);
  }

}
