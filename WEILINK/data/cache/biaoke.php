<?php
set_time_limit(0);
ignore_user_abort(1);
define('URL', 'http://dms.biaokeex.com/package/index2');
define('COOKIE', 'PHPSESSID=mr0lj8mrjvd7pbgonv7o7gkqr7; language=zh; _ga=GA1.2.1020325704.1466041744; _gat=1');
require_once __DIR__.'/phpQuery-onefile.php';


if (!empty($_GET['act']) && function_exists($_GET['act'])) {
  echo call_user_func($_GET['act'], !empty($_GET['track_no'])? $_GET['track_no']:'');
}

// 获取需要上传身份证的跟踪号
function fetchNeedIDCardTrackNo($pre_track_no = '') {
  $html = fetchPage(array('TrackingNumber'=>$pre_track_no,'NotUpload'=>1,'AuditType' => 2));
  $doc = phpQuery::newDocumentHTML($html);
  $doms = pq('#exportall-form table tbody', $doc)->find('tr');
  $pre_track_nos = '';
  foreach ($doms as $dom) {
    $text = pq('td:eq(2)', $dom)->text();
    $pre_track_nos .= ($pre_track_nos? ',':'').$text;
  }
  return $pre_track_nos;
}

// 获取审核通过的运单
function fetchPassTrackNo($pre_track_no = '') {
  $html = fetchPage(array('TrackingNumber'=>$pre_track_no,'AuditType' => 2));
  $doc = phpQuery::newDocumentHTML($html);
  $doms = pq('#exportall-form table tbody', $doc)->find('tr');
  $pre_track_nos = '';
  foreach ($doms as $dom) {
    $text = pq('td:eq(2)', $dom)->text();
    $pre_track_nos .= ($pre_track_nos? ',':'').$text;
  }
  return $pre_track_nos;
}

// 获取审核不通过的运单
function fetchUnpassTrackNo($pre_track_no = '') {
  $html = fetchPage(array('TrackingNumber'=>$pre_track_no,'AuditType' => 1));
  $doc = phpQuery::newDocumentHTML($html);
  $doms = pq('#exportall-form table tbody', $doc)->find('tr');
  $pre_track_nos = '';
  foreach ($doms as $dom) {
    $text = pq('td:eq(2)', $dom)->text();
    $pre_track_nos .= ($pre_track_nos? ',':'').$text;
  }
  return $pre_track_nos;
}

// 获取未入库的运单
function fetchUnstoreTrackNo($pre_track_no = '') {
  $html = fetchPage(array('TrackingNumber'=>$pre_track_no,'UnStored' => 1));
  $doc = phpQuery::newDocumentHTML($html);
  $doms = pq('#exportall-form table tbody', $doc)->find('tr');
  $pre_track_nos = '';
  foreach ($doms as $dom) {
    $text = pq('td:eq(2)', $dom)->text();
    $pre_track_nos .= ($pre_track_nos? ',':'').$text;
  }
  return $pre_track_nos;
}

// 获取已入库的运单
function fetchStoreTrackNo($pre_track_no = '') {
  $html = fetchPage(array('TrackingNumber'=>$pre_track_no,'UnStored' => 2));
  $doc = phpQuery::newDocumentHTML($html);
  $doms = pq('#exportall-form table tbody', $doc)->find('tr');
  $pre_track_nos = '';
  foreach ($doms as $dom) {
    $text = pq('td:eq(2)', $dom)->text();
    $pre_track_nos .= ($pre_track_nos? ',':'').$text;
  }
  return $pre_track_nos;
}

function fetchToken() {
  return preg_replace('/.*?<input\s+type="hidden"\s+id="form__csrf_token".*?value="([^"]+)".*/is', '$1', fetchPage());
}

function fetchPage($params = array()) {
  $c = curl_init(URL);
  curl_setopt($c, CURLOPT_VERBOSE, 1);
  curl_setopt($c, CURLOPT_COOKIE, COOKIE);
  curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);

  if ($params) {
    $params = array('form' => array_merge(array(
        'TrackingCenterCode' => '',
        'TrackingNumber' => '',
        'channName' => '',
        'SendStartDate' => '',
        'SendEndDate' => '',
        'HasPaied' => 0,
        'ChannelCode' => '',
        'HasPrepaid' => '',
        'NoPaying' => '',
        'UnStored' => 0,
        'NotUpload' => 0,
        'OrderType' => '',
        'AuditType' => 2,
        '查询' => '',
        '_csrf_token' => fetchToken()
    ),$params));
    curl_setopt($c, CURLOPT_POST, 1);
    curl_setopt($c, CURLOPT_POSTFIELDS, http_build_query($params));
  }

  $html = curl_exec($c);
  curl_close($c);

  return $html;
}
