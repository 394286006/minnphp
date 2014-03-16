<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
//include '../setting.php';
require_once APPROOT.'util/SecurityUserMap.php';
require_once 'IBase.php';
require_once APPROOT.'util/DBUtil.php';
require_once APPROOT.'util/MinnUtil.php';
 date_default_timezone_set('Asia/Shanghai');
abstract class Base implements IBase{
	
	/**
	 * 获取随机数
	 */
	public final static function getSRM(){
		return SecurityUserMap::getSecurityRandom();
	}
	/**
	 * @param  $info
	 */
	public function addInfo($table,$type,$keyArr) {
		$type->id=time().self::getSRM();
		if($type->createDate==''){
		   date_default_timezone_set('PRC');
           $mdt=new DateTime();
           $type->createDate=$mdt->format('Y-m-d H:i:s');
		}
		$sql=MinnUtil::buildInserSql($table,$type,$keyArr);
//		echo $sql;
		 $result=self::execute($sql);
		
	    return $result;
	}

	/**
	 * @param  $info
	 */
	public function updateInfo($table,$type,$identifire) {
		
//		$type->id=time().self::getSRM();
		
		$sql=MinnUtil::buildUpdateSql($table,$type,$identifire);
		echo $sql;
	    $result=self::execute($sql);
	    
	    return $result;
	}

	/**
	 * 检查传过来参数的合法性
	 *
	 * @param 需要限制的字段 $jsonStr
	 * @param 类对象 $vo
	 */
    public function checkNotNullProperty($jsonStr,$vo){
    	 $npp="";
    	 $index=0;
    	 if(ereg("^{.*}$",$jsonStr)){
             $str=substr($jsonStr,1,strlen($jsonStr)-2);
             $arr=split(",",$str);
             for($i=0; $i < count($arr); $i++){
             	if(ereg(".*[:].*",$arr[$i]))
             	{
             		$narr=split(":",$arr[$i]);
             		$key=substr($narr[0],1,strlen($narr[0])-2);
             		$value=substr($narr[1],1,strlen($narr[1])-2);
             		if($index>0){
             			$npp=$npp.",";
             		}
             		if($vo->$key==''){
             			$index++;
             	      $npp=$npp.$key.":".$value;
             		}
             	  
             	}
             }
          }	
          return $npp;
    }
    
    public static function getTotalCount($conn,$table,$condi){
    	$recordCount=0;
	        $sql="select count(id) as c from .$table".$condi;
			$result=@mysql_query($sql,$conn);
			  if($row =@mysql_fetch_array($result)) {
			  	$recordCount=$row['c'];
			  }
		 return $recordCount;
    }
    public static function getTotalCountBySql($conn,$sql){
    	$recordCount=0;
	       // $sql="select count(id) as c from .$table".$condi;
			$result=@mysql_query($sql,$conn);
			  if($row =@mysql_fetch_array($result)) {
			  	$recordCount=$row['c'];
			  }
		 return $recordCount;
    }
    private function execute($sql){
    	try{
    		
    	 $conn=DBUtil::getConnection();
    	 $re= @mysql_query($sql,$conn) or die(@mysql_error());
    	 DBUtil::closeConn($conn);
    	 
    	return $re;
    	 }catch(ErrorException $e)
	    {
         
	  	  echo $e;
	    }
	   
	   
    }
    
    public function getCurDate(){
    	return date('Y-m-d H:i:s');
    }
	
}
?>