<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
include '../../setting.php';
require_once 'Product.php';
require_once APPROOT.'photo/Photo.php';
require_once 'IProductService.php';
require_once APPROOT.'util/MinnUtil.php'; 
require_once APPROOT.'base/Base.php';
require_once APPROOT.'util/MessageUtil.php';
require_once APPROOT.'base/JSON.php';
@session_start();
class ProductService  implements IProductService{
	

	/**
	 * 产品查询
	 *
	 * @param unknown_type $condition
	 */
	public function queryProduct($info){
		$info=substr($info,1);		
		$re=json_decode($info);		
		$_sid=$re->_sid;
		$type_name= $re->type_name;
		$type_maxcategory_id= $re->type_maxcategory_id;
		$type_category_id= $re->type_category_id;
		$type_price_start= $re->type_price_start;
		$type_price_end= $re->type_price_end;
		$pageIndex= $re->pageIndex;
		$pageSize= $re->pageSize;
		$recordCount=$re->recordCount;
		$rowStart=$pageIndex*$pageSize;
       
	    //if($_sid==$_SESSION['securitykey']||$recordCount==-1){
	    if($_sid!=''||$recordCount==-1){
//		if(true){

		try{
			$conn=DBUtil::getConnection();
			//if($recordCount==-1){
			 // $recordCount=Base::getTotalCount($conn,'merchandise');
			//}
			$msql="select m.*,c.pid as parent_category_id,c.name as categoryName,
			(select name from category where id=c.pid) as parentCategoryName,
			(select percend from discount where dc_id=m.id and type=2) as _discount
	          from merchandise m,category c where c.id=m.category_id and m.name like '%$type_name%' ";
	        $totalsql=" select count(m.id) as c from merchandise m,category c where c.id=m.category_id and m.name like '%$type_name%' ";  
	        if($type_price_start!=''){
	        	$msql.=" and m.price>=$type_price_start";
	        	$totalsql.=" and m.price>=$type_price_start";
	        }
		    if($type_price_end!=''){
	        	$msql.=" and m.price<=$type_price_end";
	        	$totalsql.=" and m.price<=$type_price_end";
	        }
            if($type_maxcategory_id!=''){
            	$msql.=" and m.category_id in(select c.id from category c where c.pid='$type_maxcategory_id' ";
            	$totalsql.=" and m.category_id in(select c.id from category c where c.pid='$type_maxcategory_id' ";
            }
		   if($type_category_id!=''){
            	$msql.=" and  c.id='$type_category_id' ";
            	$totalsql.=" and  c.id='$type_category_id' ";
            }
            if($type_maxcategory_id!=''){
            	$msql.=")";
            	$totalsql.=")";
            }
            
           
			if($recordCount==-1){
			  $recordCount=Base::getTotalCountBySql($conn,$totalsql);
			
			}
            
	        $msql.=" order by createDate desc limit $rowStart,$pageSize";
	
	         $result=@mysql_query($msql,$conn) or die(@mysql_error());
	          
			 $arr=array();
			  while ($row =@mysql_fetch_array($result)) {
			  		 $v=new Product();	
			  		 $v->id=$row['id'];
			  		 $v->name=$row['name'];
			  		 $v->category_id=$row['category_id'];
			  		 $v->categoryName=$row['categoryName'];
			  		 $v->descript=$row['descript'];
			  		 $v->downTime=$row['downTime'];
			  		 $v->pcount=$row['pcount'];
			  		 $v->weight=$row['weight'];
			  		 $v->price=$row['price'];
			  		 $v->upTime=$row['upTime'];
			  		 $v->parent_category_id=$row['parent_category_id'];
			  		 $v->parentCategoryName=$row['parentCategoryName'];
			  		 $v->_discount=$row['_discount'];
			  		 $v->otherpath=$row['otherpath'];
			  		 $v->_qty=1;
			  		 $photos=array();
			  		 $psql="select * from mcd_phone where mcd_id='".$row['id']."'";  
			  		 $resultp=@mysql_query($psql,$conn) or die(@mysql_error());
			  		 while ($rowp =@mysql_fetch_array($resultp)) {
			  		 	$p=new Photo();
//			  		 	$p->createDate=$rowp['createDate'];
//			  		 	$p->creator=$rowp['creator'];
//			  		 	$p->id=$rowp['id'];
//			  		 	$p->imgname=$rowp['imgname'];
			  		 	$p->imgpath=$rowp['imgpath'];
			  		 	$p->descript=$rowp['descript'];
//			  		 	$p->level1type=$rowp['level1type'];
//			  		 	$p->level2type=$rowp['level2type'];
//			  		 	$p->phone_order=$rowp['phone_order'];
//			  		 	$p->sourcename=$rowp['sourcename'];
			  		 		
			  		 	 array_push($photos,$p);
			  		 }
			  		
			  		$v->_photos=$photos; 
			  	  array_push($arr,$v);
			  }
			//  $recordCount=count($arr);
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
}
?>