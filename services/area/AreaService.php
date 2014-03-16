<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
include '../setting.php';
require_once 'Area.php';
require_once 'IAreaService.php';
require_once APPROOT.'util/MinnUtil.php'; 
require_once APPROOT.'base/Base.php';
require_once APPROOT.'util/MessageUtil.php';
require_once APPROOT.'base/JSON.php';
@session_start();
class AreaService implements IAreaService{
	

	/**
	 * 查找
	 * @param  $condition
	 */
	public function query($condition) {
		
		$re=json_decode($condition);
        $_sid=$re->_sid;
         if($_sid==$_SESSION['securitykey']){
		    $recordCount=-1;
			try{
			$conn=DBUtil::getConnection();
			if($recordCount==-1){
			  //$recordCount=Base::getTotalCount($conn,'province');
			}
			$sql="select m.*,(select opr_name_ch from operator where id=m.creator) as _creatorName 
			  from province m";
			  $result=@mysql_query($sql,$conn) or die(@mysql_error());
	          
			 $arr=array();
			  while ($row =@mysql_fetch_array($result)) {
			  		 $v=new Province();	
			  		 $v->id=$row['id'];
			  		 $v->name=$row['name'];
			  		 $v->createDate=$row['createDate'];
			  		 $v->creator=$row['creator'];
			  		 $v->_creatorName=$row['_creatorName'];
			  		 
					  $csql="select m.*,(select opr_name_ch from operator where id=m.creator) as _creatorName 
					  from city m where m.p_id='".$row['id']."'";
					  $cresult=@mysql_query($csql,$conn) or die(@mysql_error());
			          
					 $carr=array();
					  while ($crow =@mysql_fetch_array($cresult)) {
					  	 $cv=new City();
					  	 $cv->id=$crow['id'];
					  	 $cv->name=$crow['name'];
					  	 $cv->createDate=$crow['createDate'];
			  		     $cv->creator=$crow['creator'];
			  		     $cv->_creatorName=$crow['_creatorName'];
			  		     	
					     $tsql="select m.*,(select opr_name_ch from operator where id=m.creator) as _creatorName  
					  		from town m where m.c_id='".$crow['id']."'";
					 	  $tresult=@mysql_query($tsql,$conn) or die(@mysql_error());
					      $tarr=array();
					     while ($trow =@mysql_fetch_array($tresult)) {
						  	 $tv=new Town();
						  	 $tv->id=$trow['id'];
						  	 $tv->name=$trow['name'];
						  	 $tv->createDate=$trow['createDate'];
				  		     $tv->creator=$trow['creator'];
				  		     $tv->_creatorName=$trow['_creatorName'];	
                             array_push($tarr,$tv);
					     }
			  		     $cv->_towns=$tarr;
					  	 array_push($carr,$cv);
				 }
					$v->_citys=$carr;  
				  array_push($arr,$v);
				
			  }
			    $recordCount=count($arr);
			  $message = json_encode($arr); 
			  $messageSucess=1;
		}catch(Exception $e){
			  $messageSucess=0;
			  $message="查询数据失败";
	     }
          }else{
         	$messageSucess=0;
	        $message='非法操作！';
         }
		return MessageUtil::getMessage($messageSucess,'array',$message,$recordCount);
	}
	
	/**
	 * 生成菜单
	 *
	 * @return unknown
	 */
	public function generatorJsonMenu($condition) {
		   
		$re=json_decode($condition);
        $_sid=$re->_sid;
       if($_sid==$_SESSION['securitykey']){
		$sql0="select * from province ";
		 try{
		 	 $conn=DBUtil::getConnection();
			$result0=@mysql_query($sql0,$conn) or die(@mysql_error());
		
			$message=array();
			  while ($row0 =@mysql_fetch_array($result0)) {
			  	$province=new ProvinceMenu();
			  	$province->name=$row0['name'];
			  	$province->id=$row0['id'];
			  	$citys=array();
			    $sql1="select * from city ct where ct.p_id='".$row0['id']."'";
			    $result1=@mysql_query($sql1,$conn);
			   	 while ($row1 =@mysql_fetch_array($result1)) {
	   		 		$city=new CityMenu();
			  	    $city->name=$row1['name'];
			  	    $city->id=$row1['id'];
	  		        $city->p_id=$row1['p_id'];
	  		        $sql2="select * from town ct where ct.c_id='".$row1['id']."'";
	  		        $towns=array();
	  		        $result2=@mysql_query($sql2,$conn);
			  		while ($row2 =@mysql_fetch_array($result2)) {
		  		     	 $town=new TownMenu();
				  	     $town->name=$row2['name'];
				  	     $town->id=$row2['id'];
				  	     $town->c_id=$row2['c_id'];
		  		         array_push($towns,$town);
			  		 }
	  		      $city->children=$towns;
	  		      array_push($citys,$city);
			  	 }
			   $province->children=$citys;
			   array_push($message,$province);
			  }
			
			  $messageSucess=1;
	     }catch(Exception $e){
			  $messageSucess=0;
			  $message="生成菜单数据失败";
	     }            
        $wenjian = fopen(APPROOT.AREAMENU,'w');
            if($wenjian){
                fwrite($wenjian,urlencode(json_encode($message))); 
                $message='生成菜单成功!';
            }        
            
	 }else{
         	$messageSucess=0;
	        $message='非法操作！';
         }
        return MessageUtil::getMessage($messageSucess,'array',$message);      
	}



}
?>