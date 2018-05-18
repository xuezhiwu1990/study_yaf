<?php
/**
 * @name SampleModel
 * @desc sample数据获取类, 可以访问数据库，文件，其它系统等
 * @author root
 */
//引入自动加载包
require __DIR__ . '/../../vendor/autoload.php';

use Nette\Mail\Message;

class MailModel {
    public $errno = 0;
    public $errmsg = '';  
    private $_db = null;
    public function __construct(){
    	$this->_db = new PDO("mysql:host=127.0.0.1;dbname=imooc","root","123456");
    }   
    public function send($uid , $title,$contents){
	$uid = 2018;
	$title = '新浪给你的入职Offer';
	$contents = '小伙子表现的很机智';
	
	$mail = new Message;
	$mail->setFrom(1111111111)
	     ->addTo('xuezhiwu001@126.com')
	     ->setSubject($title)
	     ->setBoby($contents);
	$mailer = new Nette\Mail\SmtpMailer([
		'host' => 'smtp.126.com',
		'username' => '15117995393@126.com',
		'password' => 'A123456*',
		'secure' => 'ssl',
	]);
	$rep = $mailer->send($mail);
	var_dump($rep);
	//return true;	
    }
    	
}
