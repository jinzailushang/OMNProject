<?php

/**
 * 微信
 */
require_once __DIR__.'/Weixin.php';
class indexControl extends Weixin
{
	private $FromUserName, $ToUserName, $CreateTime;

	public function __construct()
	{
	}

	/**
	 * 默认入口方法
	 */
	public function indexOp()
	{
		if (!empty($_GET['signature'])) {
			if (!$this->_checkSignature()) {
				echo 'Access denied!';
				return;
			}

			$post_data = file_get_contents('php://input');
			file_put_contents('../data/log/wexin.log', date('Y-m-d H:i')."\n--\n".$post_data."\n\n\n\n", FILE_APPEND);

			if (empty($post_data)) {
				echo !empty($_GET['echostr'])? $_GET['echostr']: '';
				return;
			}

			require_once 'include/phpQuery.php';

			phpQuery::newDocumentXML($post_data);

			// retrive data
			$this->ToUserName = pq('ToUserName')->text();
			$this->FromUserName = pq('FromUserName')->text();
			$this->CreateTime = pq('CreateTime')->text();
			$MsgType = pq('MsgType')->text();
			$Event = pq('Event')->text();

			$response = '';

			if ($this->ToUserName && $this->FromUserName && $this->CreateTime && $MsgType) {
				if ($Event) {
					if (0 === strcasecmp($Event, 'subscribe')) {
						$response = $this->_get_reply('SUBSCRIBE');
						if (!$response) {
							return;
						}
					} elseif (0 === strcasecmp($Event, 'click')) {
						$EventKey = pq('EventKey')->text();
						if (0 === stripos($EventKey, 'REPLY_')) {
							$response = $this->_get_reply($EventKey);
						}
					}
				} elseif ($MsgType == 'text') {
					$content = pq('Content')->text();
					if (0 === stripos($content, 'wl')) {
						$result = $this->_logisticsInfo(pq('Content')->text());
						$response = $this->_format_text($result);
					} else {
						$response = $this->_get_reply($content);
					}
				}
			}

			file_put_contents('../data/log/weixin.log', date('Y-m-d H:i')."\n--response:\n".$response."\n\n\n\n", FILE_APPEND);

			echo $response? $response: $this->_format_text('无相关内容!');
		} else {
			echo 'Access denied!!';
		}
	}



	/**
	 * 定义菜单
	 */
	public function setMenuOp()
	{
		$config = array('button'=>array());
		$first_level_menus = Model()->query("SELECT `menu_id`, `name`, `type`, `key`, `url` FROM wx_menu WHERE parent <=> 0 ORDER BY sort DESC, menu_id ASC");
		foreach ($first_level_menus as $fk=>$fv) {
			$second_level_menus = Model()->query("SELECT `name`, `type`, `key`, `url` FROM wx_menu WHERE parent <=> {$fv['menu_id']} ORDER BY sort DESC, menu_id ASC");
			$menu = array();
			if ($second_level_menus) {
				$menu['name'] = $fv['name'];
				$menu['sub_button'] = array();
				foreach ($second_level_menus as $smenu) {
					if ($smenu['type'] == 'click') {
						unset($smenu['url']);
					} else {
						$smenu['url'] = htmlspecialchars_decode($smenu['url']);
						unset($smenu['key']);
					}
					$menu['sub_button'][] = $smenu;
				}
			} else {
				unset($fv['menu_id']);
				if ($fv['type'] == 'click') {
					unset($fv['url']);
				} else {
					$fv['url'] = htmlspecialchars_decode($fv['url']);
					unset($fv['key']);
				}
				$menu = $fv;
			}
			$config['button'][] = $menu;
		}

		$access_token = $this->_get_access_token();
		if (!$access_token) {
			throw new Exception('获取accesstoken失败');
		}

		// 删除菜单
		$rest = $this->_rest();
		$rest->url = "https://api.weixin.qq.com/cgi-bin/menu/delete?access_token={$access_token}";
		$rest->httpheader = array('Content-Type' => 'text/plain');
		$rest->post();

		// 创建菜单
		$rest = $this->_rest();
		$rest->url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token={$access_token}";
		$rest->params = json_encode($config, JSON_UNESCAPED_UNICODE);
		$rest->httpheader = array('Content-Type' => 'text/plain');
		$rest->post();

		//echo '<pre>';
		//print_r($rest->params);
		//print_r($rest->response);
		echo $rest->response;
	} 


	public function article_detailOp() {
		isset($_GET['article_id']) && is_numeric($_GET['article_id']) && $_GET['article_id'] > 0 || die('Invalid article_id');
		$row = Model()->query("SELECT content FROM wx_article WHERE article_id = '{$_GET['article_id']}' LIMIT 1");
		$row = $row[0];
		$title = '';
		//  抓取h1/h2/h3/h4/h5/strong
		if (FALSE !== strpos($row['content'],'<h1>')) {
			$title = preg_replace('/.*?<h1[^>]*>(.*?)<\/h1>.*/is', '$1', $row['content']);
		} elseif (FALSE !== strpos($row['content'],'<h2')) {
			$title = preg_replace('/.*?<h2[^>]*>(.*?)<\/h2>.*/is', '$1', $row['content']);
		} elseif (FALSE !== strpos($row['content'],'<h3')) {
			$title = preg_replace('/.*?<h3[^>]*>(.*?)<\/h3>.*/is', '$1', $row['content']);
		} elseif (FALSE !== strpos($row['content'],'<h4')) {
			$title = preg_replace('/.*?<h4[^>]*>(.*?)<\/h4>.*/is', '$1', $row['content']);
		} elseif (FALSE !== strpos($row['content'],'<h5')) {
			$title = preg_replace('/.*?<h5[^>]*>(.*?)<\/h5>.*/is', '$1', $row['content']);
		} elseif (FALSE !== strpos($row['content'],'<strong')) {
			$title = preg_replace('/.*?<strong[^>]*>(.*?)<\/strong>.*/is', '$1', $row['content']);
		} elseif (FALSE !== strpos($row['content'],'<p')) {
			$title = preg_replace('/.*?<p[^>]*>(.*?)<\/p>.*/is', '$1', $row['content']);
		}
		$title = preg_replace('/(<[^>]+>|&nbsp;|\s)+/is', '', $title);

		if (!$title) {
			$title = mb_substr(preg_replace('/(<[^>]+>|&nbsp;|\s)+/is', '', $row['content']),0,15);
		}

		echo<<<EOF

<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <link rel="dns-prefetch" href="//res.wx.qq.com">
    <link rel="dns-prefetch" href="//mmbiz.qpic.cn">
    <link rel="shortcut icon" type="image/x-icon" href="http://res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/icon/common/favicon22c41b.ico">
    <title>{$title}</title>
    <link rel="stylesheet" type="text/css" href="https://res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/page/appmsg/page_mp_article_improve2eb52b.css">
    <!--[if lt IE 9]>
    <link rel="stylesheet" type="text/css" href="https://res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/page/appmsg/page_mp_article_improve_pc2c9cd6.css">
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="https://res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/page/appmsg/page_mp_article_improve_combo2eb52b.css">
  </head>
  <body id="activity-detail" class="zh_CN mm_appmsg" ontouchstart="">
    <div id="js_article" class="rich_media">
      <div class="rich_media_inner">
        <div id="page-content">
{$row['content']}
        </div>
      </div>
    </div>
  </body>
</html>
EOF;
	}

	/**
	 * 获取物流信息
	 */
	private function _logisticsInfo($logisticsNumber) {
		if (empty($logisticsNumber)) {
			return '无效的物流单号';
		}

		$order_info = Model('order')->getOrderInfo(array('shipping_code'=>$logisticsNumber));
		if (!$order_info) {
			return '无效的物流单号!';
		}

		$track_no = $order_info['track_no'] ? $order_info['track_no'] : $order_info['pre_track_no'];
		if ($track_no) {
			//$list = array();
			$list = '';
			$result = Model('package_service')->queryOrderStatus($track_no);
			if ($result->ResponseResult == 'Success') {
				$data = (array)$result->Data->TraceFlow->TraceStatus;
				if (!isset($data[0])) {
					$data[0] = $data;
				}
				foreach ($data as $d) {
					//$list[] = array(
					//	'time' => preg_replace('/(\d{4}\-\d{2}\-\d{2})T(\d{2}:\d{2}:\d{2})(\.\d{1,3})?/s', '$1 $2', $d->CreatedTime),
					//	'info' => $d->StatusDesc
					//);
					$list .= ($list? "\n":''). preg_replace('/(\d{4}\-\d{2}\-\d{2})T(\d{2}:\d{2}:\d{2})(\.\d{1,3})?/s', '$1 $2', $d->CreatedTime).' '. $d->StatusDesc;
				}
			}

			return $list? $list : '暂无物流信息';

		} else {

			return '无效的物流单号!!';

		}
	}


	/**
	 * 获取微信accesstoken
	 */
	private function _get_access_token()
	{
		$data = file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->appid}&secret={$this->secret}");	
		$data = json_decode($data);

		return $data? $data->access_token: NULL;
	}


	/**
	 * 获取rest句柄
	 */
	private function _rest()
	{
		require_once 'include/sdk/restclient.php';
		$rest =  new restclient;
		$rest->timeout = 0;
		return $rest;
	}


	/**
	 * 检测signature
	 */
	private function _checkSignature() {
		$signature = $_GET['signature'];
		$timestamp = $_GET['timestamp'];
		$nonce = $_GET['nonce'];

		$tmpArr = array($this->token, $timestamp, $nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode($tmpArr);
		$tmpStr = sha1($tmpStr);

		if ($tmpStr == $signature) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function _format_text($text) {
		$tmpl = <<<EOF
<xml>
<ToUserName><![CDATA[{$this->FromUserName}]]></ToUserName>
<FromUserName><![CDATA[{$this->ToUserName}]]></FromUserName>
<CreateTime>{$this->CreateTime}</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[%s]]></Content>
</xml>
EOF;

		return sprintf($tmpl, $text);
	}

	private function _format_news($data) {
		$tmpl = <<<EOF
<xml>
<ToUserName><![CDATA[{$this->FromUserName}]]></ToUserName>
<FromUserName><![CDATA[{$this->ToUserName}]]></FromUserName>
<CreateTime>{$this->CreateTime}</CreateTime>
<MsgType><![CDATA[news]]></MsgType>
<ArticleCount>%d</ArticleCount>
<Articles>
%s
</Articles>
</xml>
EOF;
		$response = '';
		foreach ($data as $c) {
			$response .= <<<EOF
<item>
<Title><![CDATA[{$c['Title']}]]></Title>
<Description><![CDATA[{$c['Description']}]]></Description>
<PicUrl><![CDATA[{$c['PicUrl']}]]></PicUrl>
<Url><![CDATA[{$c['Url']}]]></Url>
</item>
EOF;
		}
		return sprintf($tmpl, count($data), $response);
	}

	private function _get_reply($words)
	{
		$row = Model()->query("SELECT reply_type, reply_content FROM wx_reply WHERE words = '{$words}' LIMIT 1");
		if ($row) {
			$row = $row[0];
			if ($row['reply_type'] == 'text') {
				$response = $this->_format_text($row['reply_content']);
			} elseif ($row['reply_type'] == 'news') {
				$content = json_decode(htmlspecialchars_decode($row['reply_content']), TRUE);
				$response = $this->_format_news($content);
			}
			// 记录
			Model()->execute("INSERT INTO wx_reply_log SET
from_user = '{$this->FromUserName}',
words = '{$words}',
reply_type = '{$row['reply_type']}',
reply_content = '{$row['reply_content']}',
reply_time = '{$this->CreateTime}'
");
			return $response;
		}
	}
}
