<?php
/**
 * sftp上传下载
 * @package    library
 *
 **/
defined('InOmniWL') or exit('Access Invalid!');

class sFtp {
        
	//FTP服务器地址
	private $strServer = "";
	//FTP服务器端口
	private $strServerPort = "";
	//FTP服务器账户
	private $strServerUsername = "";
	//FTP服务器密码
	private $strServerPassword = "";
	//FTP远程文件夹
	private $path   = '';
	
	public function setValue($name){
		foreach($name as $k=>$v){
			$this->$k = $v;
		}
		
	}
	//FTP上传
	public function put($filename,$localfile){

		if(!isset($filename) || empty($filename)){
			return array('status'=>'0','msg'=>'上传文件为空，请重试');
		}	

		if(function_exists('ssh2_connect')){
			
			//连接到FTP服务器
			$resConnection = ssh2_connect($this->strServer, $this->strServerPort);
			
			if(ssh2_auth_password($resConnection, $this->strServerUsername, $this->strServerPassword)){
				//初始化 SFTP
				$resSFTP = ssh2_sftp($resConnection);
			
				$file = $localfile;//上传文件路径
			
				$path =  $this->path .'/'. $filename;//远程服务器文件上传
					
				$sftpStream = fopen('ssh2.sftp://'.$resSFTP.$path, 'w');
				try {
						
					if (!$sftpStream) {
						return array('status'=>'0','msg'=>"打开远程文件夹失败: $path");
					}
						
					$data_to_send = file_get_contents($file);
						
					if ($data_to_send === false) {
						return array('status'=>'0','msg'=>"打开本地文件失败: $file");
					}
						
					if (fwrite($sftpStream, $data_to_send) === false) {
						return array('status'=>'0','msg'=>"传送文件失败: $file");
					}
					fclose($sftpStream);
					return array('status'=>'1','msg'=>'上传成功！');
						
				} catch (Exception $e) {
					fclose($sftpStream);
					return array('status'=>'0','msg'=>$e->getMessage());
				}
			
			} else {
				return array('status'=>'0','msg'=>'FTP服务器连接失败，请重试');
			}
		}else{
			return array('status'=>'0','msg'=>'系统不支持SSH2');
		}


	}

}