<?php
/**
 * @name IndexController
 * @author root
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class IpsController extends Yaf_Controller_Abstract {
	
	public function indexAction(){
		return $this->getAction();
	}

	public function getAction( ){
	//return false;

	$ip = $this->getRequest()->getQuery( "ip" , "" );
		if( !$ip || !filter_var($ip ,FILTER_VALIDATE_IP ) ){
                        echo json_encode(array('error' => -2001 , 'errmsg' => '请通过正确渠道提交'));
                        return false;
                 }
		
		//用model , 查Ip归属地
		$model = new IpModel();
		$data = $model->get(trim($ip));
		if( $data ){
			echo json_encode(
                        	array(
                        	'errno' => 0,
                        	'errmsg' => '',
                        	'data' => array('data' => $data)
                        	));
		} else {
			echo json_encode(
                        	array(
                        	'errno' => $model->errno,
                        	'errmsg' => $model->errmsg,
                       		 ));

		}
		return FALSE;
	}
	
	

}
