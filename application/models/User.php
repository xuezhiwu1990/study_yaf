<?php
/**
 * @name SampleModel
 * @desc sample数据获取类, 可以访问数据库，文件，其它系统等
 * @author root
 */
class UserModel {
    public $errno = 0;
    public $errmsg = '';  
    private $_db = null;
    public function __construct(){
    	$this->_db = new PDO("mysql:host=127.0.0.1;dbname=imooc","root","123456");
    }   
   

    public function register($name , $pwd) {
        $sql = "select count(*) as c from user where name = ?";
	$query = $this->_db->prepare($sql);
	$query->execute(array($name));
	$count = $query->fetchAll();
	if($count[0]['c'] != 0){
		$this->errno = -1005;
		$this->errmsg = '用户名已存在';
		return false;
	}
	if( strlen($pwd) < 8 ){
		$this->errno = -1006;
                $this->errmsg = '密码太短，请设置至少8位的密码';
                return false;
	}else{
		$password = $this->_password_generate( $pwd );
	}

	$insert_sql = "insert into `user` ( `name` ,`pwd`,`reg_time` ) VALUES ( ? , ? , ? )";
        $query = $this->_db->prepare($insert_sql);
  	$ret = $query->execute(array( $name,$password ,time() ));
	if(!$ret){
		$this->errno = -1007;
		$this->errmsg = '注册失败,写入数据失败';
		return false;
	}
	return true;
    }

	
    private function _password_generate($password){
	$pwd = md5("sqlt-xxxxxxxx".$password);
	return $pwd;	
    }	

    public function login( $name , $pwd ){
    	$sql = "select `id` , `name` ,`pwd` from `user` where `name` = ?";
        $query = $this->_db->prepare($sql);
        $query->execute(array($name));
        $ret = $query->fetchAll();
	if(!$ret || count($ret)<1 ){
                $this->errno = -1004;
                $this->errmsg = '用户查找失败';
                return false;
        }
	$user_info = $ret[0];
	if($this->_password_generate($pwd) != $user_info['pwd']){
		$this->errno = -1008;
                $this->errmsg = '用户密码不正确，请稍后重试...';
                return false;

	}
	return intval($user_info[1]);

    }

}
