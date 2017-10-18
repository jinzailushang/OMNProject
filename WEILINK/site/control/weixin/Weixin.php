<?php

/**
 * Created by IntelliJ IDEA.
 * User: MyPC
 * Date: 2016/9/13
 * Time: 14:21
 */
class Weixin extends SystemControl
{
  protected
    $appid = 'wx0bba282a7eb11af4',
    $secret = '12506fea58afe53897b67475ab8d0f6f',
    $token = 'QcKscG4OIcXDrDC7',
    $aeskey = 'Kuf5KBVNhOyhPyVSHywkQyQWQ9cGj75En0kr4bKbQhM';

  public function __construct()
  {
    parent::__construct();
  }
}