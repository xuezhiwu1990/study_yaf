<?php
/**
 * @name IndexController
 * @author root
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class MailController extends Yaf_Controller_Abstract {
	
	public function indexAction(){
	}

	/*** 新增 ***/
	public function sendAction( ){
		$submit = $this->getRequest()->getQuery("submit","0");
		if($submit != "1"){
		 	json_encode(array('errno' => -3001 , 'errmsg' => '请通过正常渠道提交'));
			return false;
		}

		//获取参数 
		$uid = $this->getRequest()->getPost("uid",false);
		$title = $this->getRequest()->getPost("title",false);
		$contents = $this->getRequest()->getPost("contents",false);
		if( !$uid || !$title || !$contents){
			echo json_encode(array('errno' => -3002 , 'errmsg' => '用户ID、标题、内容不能为空'));
			return false;
		}
		//调用Model，发邮件
		$model = new MailModel();
		if($model->send(intval($uid),trim($title),trim($contents))){
		  	
			echo json_encode(array('errno' => 0 , 'errmsg' => ''));
			return false;
		  	
		}else{
			echo json_encode(array('errno' => $this->errno , 'errmsg' => $this->errmsg));
		}
		return true;
	}
		

	

}
