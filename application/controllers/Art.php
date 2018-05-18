<?php
/**
 * @name IndexController
 * @author root
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class ArtController extends Yaf_Controller_Abstract {
	
	public function indexAction(){
		return $this->listAction();
	}

	/*** 新增 ***/
	public function addAction( $artId=0 ){
	    	
		 if( !$this->_isAdmin() ){
                	echo json_encode(array('error' => -2000 , 'errmsg' => '需要管理员才可操作'));
                	return false;
		 }
	
		$submit = $this->getRequest()->getQuery( "submit" , "0" );
		
		if( $submit != "1" ){
                        echo json_encode(array('error' => -2001 , 'errmsg' => '请通过正确渠道提交'));
                        return false;
                 }
		//获取参数
		$title = $this->getRequest()->getPost( "title" ,false );
		$contents = $this->getRequest()->getPost( "contents" , false );
		$author = $this->getRequest()->getPost("author",false);
		$cate = $this->getRequest()->getPost("cate",false);
		$artId = $this->getRequest()->getPost("artId",false);
		if( empty($title) || empty($contents) || empty($author) || empty($cate) ){
			
			echo json_encode(
				array(
				'errno' => -2100,
				'errmsg' => '标题\内容\作者\分类ID,不为空...',
				)	
			);
			return false;
		}
		$model = new ArtModel();
		$lastId = $model->add( trim($title) , trim($contents), trim($author) ,intval($cate), $artId );
		if($lastId ){
			echo json_encode(
                        	array(
                        	'errno' => 0,
                        	'errmsg' => '',
                        	'data' => array('data' => $lastId)
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
	
	
	public function editAction(){
		return TRUE;	
	}

	/** 删除 **/
	public function delAction(){
		if( !$this->_isAdmin() ){
			echo json_encode(array('errno' => -2001 , 'errmsg' => '需要管理员权限才可以操作'));
			return FALSE;
		}
		$artId= $this->getRequest()->getPost( "artId" , "0" );
		if( is_numeric($artId) && $artId ){
			$model = new ArtModel();
			if($model->del($artId)){
				echo json_encode(array('errno' => 0 , 'errmsg' => ''));
			}else{
				echo json_encode(array('error' => $model->error , 'errmsg' => $model->errmsg));
			}

		}else{
			  echo json_encode(array('errno' => -2003 , 'errmsg' => '缺少必要的参数'));
                       	  return FALSE;

		}
		return FALSE;
	
	}
	
	/** 修改状态 **/
	public function statusAction(){
		if( !$this->_isAdmin() ){
                        echo json_encode(array('errno' => -2001 , 'errmsg' => '需要管理员权限才可以操作'));
                        return FALSE;
                }
                $artId= $this->getRequest()->getPost( "artId" , "0" );
		$status = $this->getRequest()->getPost("status","offline");
		if( is_numeric($artId) && $artId ){
			$model = new ArtModel();
			if($model->status($artId , $status)){
				echo json_encode(array('errno' => 0 , 'errmsg' => ''));
			}else{
				echo json_encode(array('error' => $model->error , 'errmsg' => $model->errmsg));
			}
		}
		return false;
	}
	/** 获取文章 **/
	public function getAction(){
		$artId= $this->getRequest()->getPost( "artId" , "0" );

		if( is_numeric($artId) && $artId ){
                        $model = new ArtModel();
                        $ret = $model->get($artId);
			if($ret && !empty($ret)){
                                echo json_encode(array('errno' => 0 , 'errmsg' => '','data' => $ret));
                        }else{
                                echo json_encode(array('error' => $model->error , 'errmsg' => $model->errmsg));
                        }
                }else{
			 echo json_encode(array('errno' => -2003 , 'errmsg' => '缺少必要的参数'));
                         return FALSE;

		}

		return false;
	}
	
	/** 列表 **/
	public function listAction(){
		$pageNo = $this->getRequest()->getQuery("pageNo","1");
		$pageSize = $this->getRequest()->getQuery( "pageSize" , "10" );
		$cate = $this->getRequest()->getQuery( "cate" , "0" );
		$status = $this->getRequest()->getQuery( "status" , "online" );

		$model = new ArtModel();

		$data = $model->list($pageNo , $pageSize , $cate , $status );
		if($data){
		  	echo json_encode( array('error' => 0 , 'errmsg' => '' , 'data' => $data )); 
		}else{
			echo json_encode(array("errno" => -2012 , "errmsg" => "获取文章列表失败" ));
		}
		return false;
	}

	/** 验证管理员 **/
	public function _isAdmin(){
		return TRUE;
	}

}
