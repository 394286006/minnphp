<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
class MinnUtil{

	/**
	 *  qq 394286006
	 * @param  $jsonStr  已过时
	 * @param $vo
	 */
	public static function josonToMap($jsonStr,$vo){
//		echo 'invoke';
	 if(ereg("^{.*}$",$jsonStr)){
             $str=substr($jsonStr,1,strlen($jsonStr)-2);
             $arr=split(",",$str);
             for($i=0; $i < count($arr); $i++){
             	if(ereg(".*[:].*",$arr[$i]))
             	{
             		$pos=strpos($arr[$i],":");
             		$key=substr($arr[$i],1,$pos-2);
             		$value=substr($arr[$i],$pos+2,strlen($arr[$i])-$pos-3);
//             		if($key=="_ctype")
//             		echo $value;
             	    $vo->$key=$value;
             	}
             }
          }
	}
	/**
	 * 把json转换后的对象放入对应的对象中
	 *
	 * @param unknown_type $obj
	 * @param unknown_type $vo
	 */
	public static function obj2Map($obj,$vo){
           foreach ($vo as $key=>$val){   
               $vo->$key=$obj->$key;
           }   
	}
	
	
	/**
	 * 
	 * @param  $table
	 * @param $vo
	 * @param  $exp 
	 */
	public static function buildInserSql($table,$vo,$keyArr,$exp='_'){
	   $prp=get_object_vars($vo);
       $index=0;
       $sqlcl="insert into $table(";
       $sqlv=") values(";
       foreach ($prp as $key=>$value){
       	if(!ereg("^$exp.*",$key)){
       	  $flag=array_search($key,$keyArr);
       	 if($value!=""||$flag!=false){
       	 	if($index>0)
       	 	{
       	 		$sqlcl.=",";
       	 	    $sqlv.=",";
       	 	}
       	 	$index++;
       	 	$sqlcl.=$key;
       	 	if($flag=='int')
       	 	   $sqlv.=".$value.";
       	 	 else if($flag=='date'){
                 if($value==''){
                      $value='now()';
       	 	        $sqlv.="date_format(".$value.",'%Y-%c-%d %H:%i:%s')";
                 }else{
                 	 $sqlv.="date_format('".$value."','%Y-%c-%d %H:%i:%s')";
                 }
       	 	 }
       	 	 else
       	 	$sqlv.="'".$value."'";
       	 	
       	 }
       	}
       }
       if($index==0)
       return "no insert sql,check the param!";
       else
//       return substr($sqlcl,0,strlen($sqlcl)).substr($sqlv,0,strlen($sqlv)).")";
       return $sqlcl.$sqlv.")";
       
	}
	
		/**
	 * 
	 * @param  $table
	 * @param  $vo
	 * @param $identifier
	 * @param $exp 
	 */
	public static function buildUpdateSql($table,$vo,$identifier,$exp='_'){
	   $prp=get_object_vars($vo);
       $index=0;
       $sqlc="update $table set ";
       $sqlw=" where ";
       foreach ($prp as $key=>$value){
       	if(!ereg("^$exp.*",$key)){
       		
       	 if(!ereg("^$identifier$",$key)&&$value!=""){
       	 	if($index>0)
       	 	{
       	 		$sqlc.=",";
       	 	}
       	 	$index++;
       	 	$sqlc.=$key."=".$value;
       	 }
       	 if(ereg("^$identifier$",$key)){
       	 	$sqlw.=$identifier."='".$value."'";
       	 }
       	}
       	
       }
       if($index==0)
       return "no update sql,check the param!";
       else
       return $sqlc.$sqlw.")";
	}
}
?>