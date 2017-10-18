<?php
defined('InOmniWL') || define('InOmniWL', 1);
require __DIR__.'/../config/config.ini.php';

$db = $config['db'][1];
define('DB_HOST', $db['dbhost']);
define('DB_PORT', $db['dbport']);
define('DB_USER', $db['dbuser']);
define('DB_PASS', $db['dbpwd']);
define('DB_NAME', $db['dbname']);

function db_escape_string($string) {
  return mysqli_real_escape_string(db_handler(), $string);
}

function db_handler() {
  static $handler = NULL;
  if (is_null($handler)) {
    $handler = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT) or output_json(0, 'DB connect fail');
    $handler->query("SET NAMES 'utf8'");
  }

  return $handler;
}

function db_execute($sql) {
  $result = db_handler()->query($sql) or die(db_handler()->error.'<br>'.$sql);
  return $result;
}

function db_query($sql) {
  $result = db_execute($sql);
  $rows = array();
  if ($result) {
    while($row = $result->fetch_assoc()) {
      $rows[] = $row;
    }
  }

  return $rows;
}

//处理特殊字符
function safe_b64encode($string) {
  $data = base64_encode($string);
  $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
  return $data;
}

//解析特殊字符
function safe_b64decode($string) {
  $data = str_replace(array('-', '_'), array('+', '/'), $string);
  $mod4 = strlen($data) % 4;
  if ($mod4) {
    $data .= substr('====', $mod4);
  }
  return base64_decode($data);
}
