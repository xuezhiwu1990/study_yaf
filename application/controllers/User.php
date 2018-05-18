<?php
/**
 * @name IndexController
 * @author root
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class UserController extends Yaf_Controller_Abstract {
    public function indexAction(){
	return $this->loginAction();
    }
    public function loginAction(){
        //验证是否表单提交
        $submit = $this->getRequest()->getQuery("submit",0);
        if( $submit != 1){
                echo json_encode(array('error' => -1002 , 'errmsg' => '请通过正确途径提交'));
                return false;
        }
	//接收参数
        $uname = $this->getRequest()->getPost("uname",false);
        $pwd = $this->getRequest()->getPost("pwd",false);
        if( !$uname || !$pwd ){
                echo json_encode(array('error' => -1002 , 'errmsg' => '用户名或密码为必传参数'));
                return false;
        }
	//实例化model
	$model = new UserModel();
	$uid = $model->login(trim($uname),trim($pwd));
	if($uid){
		//设置session
		$session_start();
		$_SESSION['user_token'] = md5("salt".$_SERVER['REQUEST_TIME'].$uid);
		$_SESSION['user_token_time'] = $_SERVER['REQUEST_TIME'];
		$_SESSION['user_id'] = $uid;
		 echo json_encode(
                        array(
                        'errno' => 0,
                        'errmsg' => '',
                        'data' => array('name' => $uname)
                        )
                );

	}else{
	         echo json_encode(
                        array(
                        'errno' => $model->errno,
                        'errmsg' => $model->errmsg,
                        )
                );

	}
	return false;
    }

    public function registerAction(){
	//获取参数
	$uname = $this->getRequest()->getPost("uname",false);
	$pwd = $this->getRequest()->getPost("pwd",false);
	$request = $this->getRequest();
	if( !$uname || !$pwd ){
		echo json_encode(array('error' => -1002 , 'errmsg' => '用户名或密码为必传参数'));
		return false;
	}
	
	//model
	$model = new UserModel();
	if( $model->register(trim($uname), trim($pwd)) ){
		echo json_encode(
			array(
			'errno' => 0,
			'errmsg' => '',
			'data' => array('name' => $uname)
			)
		);
	}else{
		echo json_encode(
                        array(
                        'errno' => $model->errno,
                        'errmsg' => $model->errmsg,
                        )
                );

	}
	return false;	
}
    /** 
     * 默认动作
     * Yaf支持直接把Yaf_Request_Abstract::getParam()得到的同名参数作为Action的形参
     * 对于如下的例子, 当访问http://yourhost/miooc/index/index/index/name/root 的时候, 你就会发现不同
     */
	public function regAction($name = "User Reg") {
		//1. fetch query
		$get = $this->getRequest()->getQuery("get", "default value");
		var_dump($name);
		//2. fetch model
		$model = new SampleModel();

		//3. assign
		//$this->getView()->assign("content", $model->selectSample());
		//$this->getView()->assign("name", $name);

		//4. render by Yaf, 如果这里返回FALSE, Yaf将不会调用自动视图引擎Render模板
        	return false;
	}
}
