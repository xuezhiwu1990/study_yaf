<?php
/**
 * @name SampleModel
 * @desc sample数据获取类, 可以访问数据库，文件，其它系统等
 * @author root
 */
class ArtModel {
    public $errno = 0;
    public $errmsg = '';  
    private $_db = null;
    public function __construct(){
    	$this->_db = new PDO("mysql:host=127.0.0.1;dbname=imooc","root","123456");
	/** 设置下面的话，PDO在拼SQL的时候 ， 把int 0 转成string 0 */
	$this->_db->setAttribute( PDO::ATTR_EMULATE_PREPARES , false );
    }   
   
    /** 添加内容 **/
    public function add( $title ,$contents ,$author ,$cate , $artId = 0 ) {
       	$isEdit = FALSE;
	if( $artId != 0 && is_numeric($artId) ){
		/** edit **/
	        $sql = "select count(*) as c from art where id = ?";
       		$query = $this->_db->prepare($sql);
        	$query->execute(array($artId));
        	$ret = $query->fetchAll();
		if(!$ret || count($ret)!=1 ){
                	$this->errno = -2003;
                	$this->errmsg = '找不到你要编辑的文章';
                	return false;
        	}
		$isEdit = TRUE;
	} else {
		/** add **/
		$sql = "select count(*) from `cate` where id = ?";
                $query = $this->_db->prepare($sql);
                $query->execute(array($cate));
                $ret = $query->fetchAll();
                if(!$ret || $ret[0][0] = 0 ){
                        $this->errno = -2005;
                        $this->errmsg = '找不到对应ID的分类信息，请先创建分类';
                        return false;
                }

	}
	
	/** add/edit 更新内容*/
	$data = array( $title , $contents , $author , intval($cate), intval($artId) );
	if( !$isEdit ){
		$add_sql = "insert into `art` ( `title` ,`contents` , `author`,`cate`) VALUES ( ? , ? , ? , ? ) ";
		$query = $this->_db->prepare($add_sql);
	}else{
		$edit_sql = "update `art` set `title`=? , `contents`=?,`author`=?,`cate` =? where `id`=? ";
		$query = $this->_db->prepare($edit_sql);	
	}
	$ret = $query->execute( $data );
	if(!$ret){
		$this->errno = -2006;
		$this->errmsg = "操作文章数据表失败,ErrInfo:".end($query->errorInfo());
		return false;
	}
	return true;
    }
    /** 删除文章 **/
    public function del ( $artId ){
	$sql = "delete from art where id = ?";
        $query = $this->_db->prepare($sql);
        $ret = $query->execute(array(intval($artId)));
 	if(!$ret){
                  $this->errno = -2007;
                  $this->errmsg = '删除失败,ErrInfo:'.end($query->errorInfo());
                   return false;
         }
	 return TRUE;
	
     }
     /** 更改线索状态  **/
     public function status($artId , $status = 'offline' ){
	$sql = "update `art` set `status` = ? where id = ? ";
        $query = $this->_db->prepare($sql);
        $ret = $query->execute(array($status , intval($artId)));
        if(!$ret){
                  $this->errno = -2007;
                  $this->errmsg = '修改失败,ErrInfo:'.end($query->errorInfo());
                   return false;
         }
         return TRUE;
	}
	
	/** 差询文章信息 **/
	public function get($artId){
		/** 查询 **/
                $sql = "select *  from `art` where id = ?";
                $query = $this->_db->prepare($sql);
                $query->execute(array($artId));
                $ret = $query->fetchAll(PDO::FETCH_ASSOC);
                if(!$ret ){
                        $this->errno = -2005;
                        $this->errmsg = '找不到对应ID的分类信息，请先创建分类';
                        return false;
                }
		$cate_id = $ret[0]['cate'];
		$cate_sql = "select `name` from `cate` where id = ?";
                $cate_query = $this->_db->prepare($cate_sql);
                $cate_query->execute(array($cate_id));
                $cate_ret = $cate_query->fetchAll(PDO::FETCH_ASSOC);
		$name = empty($cate_ret[0]['name']) ? '' : $cate_ret[0]['name'];
		$ret[0]['cateName'] = $name;
		return $ret[0];	
	}
	
	/** 列表 **/
	public function list( $pageNo = 1 , $pageSize = 10 , $cate = 0 , $status = 'online' ){
		$start = ( $pageNo - 1 ) * $pageSize ;
		if( 0 == $cate ){
			
			$sql = "select `id`,`title`,`contents`,`author`,`cate`,`ctime`,`status` from art where `status` = ? order by `ctime` desc limit ? ,?";
			$filter = array( $status , $start , $pageSize );
		}else{
			$sql = "select `id`,`title`,`contents`,`author`,`cate`,`ctime`,`status` from art where `status` = ? AND `cate`= ? order by `ctime` desc limit ? ,?";
			$filter = array( $status , $cate , $start , $pageSize );
		}
		
		$query = $this->_db->prepare($sql);
		$query->execute($filter);
		$ret = $query->fetchAll(PDO::FETCH_ASSOC);
		
		if(!$ret){
			$this->errno = -2011;
			$this->errmsg = '获取文章列表失败';
			return false;
		}
		return $ret; //var_dump($ret);
	}
}
