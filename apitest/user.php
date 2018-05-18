<?php
require_once __DIR__ . '/../vendor/autoload.php';
use \Curl\Curl;
$host = 'http://192.168.47.128/';
$curl = new Curl();
$uname = 'apitest_uname_'.rand();
$pwd = 'apitest_pwd_'.rand();

/***
* 注册接口认证
**/
for ($i = 0; $i<=100 ; $i ++){
$curl->post($host.'/user/register',array('uname' => $uname.$i , 'pwd' => $pwd.$i ));


if($curl->error){
 die("Error:".$curl->errorCode.":".$curl->errorMessage . "\n");
}else{
 $reg = json_decode($curl->response ,true);
 echo '注册用户成功'.$uname,'密码'.$pwd.PHP_EOL;
}
}
/**
* 登陆接口认证
*/
echo 'check done.';

