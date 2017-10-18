<?php

/**
 * banner
 * copyright 2016-06-23, jack
 */
defined('InOmniWL') or exit('Access Invalid!');

class BannerControl extends SystemControl
{

  public function __construct()
  {

  }

  public function indexOp() {
    $this->output(1, null, array(
      //array('url' => '')
    ));
  }
}
