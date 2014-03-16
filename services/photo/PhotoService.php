<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
include '../setting.php';
require_once 'Photo.php';
require_once APPROOT.'ctype/Ctype.php';
require_once 'IPhotoService.php';
require_once APPROOT.'util/MinnUtil.php'; 
require_once APPROOT.'base/Base.php';
require_once APPROOT.'util/MessageUtil.php';
require_once APPROOT.'base/JSON.php';
class PhotoService extends Base implements IPhotoService{
	
	
	/**
	 * 添加商品
	 * @param $info
	 */

	public function add($info){
		  $merchandise = new Merchandise();
		  $ctype = new Ctype();
	      $discount=new Discount();
	      
          $merchandiseobj= json_decode($info);
          $ctypeobj=$merchandiseobj->_ctype;
          $photoobjs=$merchandiseobj->_photos;
          $discountobj=$merchandiseobj->_discount;
          MinnUtil::obj2Map($merchandiseobj,$merchandise);
          MinnUtil::obj2Map($ctypeobj,$ctype);
          MinnUtil::obj2Map($discountobj,$discount);
           
          $keyArr=array("date"=>"createDate");
          $photos=array();
         try{
         	 $conn=DBUtil::getConnection();
         	 @mysql_query('START TRANSACTION') or die(mysql_error());
         	 $merchandise->id=time().self::getSRM();
         	 $msql=MinnUtil::buildInserSql("merchandise",$merchandise,$keyArr);
    	     @mysql_query($msql,$conn) or die(@mysql_error());
    	     $discount->dc_id=$merchandise->id;
    	     $discount->id=time().self::getSRM();
    	     $dsql=MinnUtil::buildInserSql("discount",$discount,$keyArr);
    	     @mysql_query($dsql,$conn) or die(@mysql_error());
    	     
    	     for($j=0;$j<count($photoobjs);$j++){
    	     	$photo=new Photo();
    	     	 MinnUtil::obj2Map($photoobjs[$j],$photo);
    	     	 $photo->mcd_id=$merchandise->id;
    	     	 $photo->id=time().self::getSRM();
    	     	 $psql=MinnUtil::buildInserSql("mcd_phone",$photo,$keyArr);
    	     	 @mysql_query($psql,$conn) or die(@mysql_error());
    	     	 array_push($photos,$photo);
    	     }
    	     
    	     @mysql_query('COMMIT') or die(mysql_error());
    		 $messageSucess=1;
          	 $message="添加成功";
          	  DBUtil::closeConn($conn);
         }catch(Exception $e){
         	 @mysql_query('ROLLBACK');
         	 $messageSucess=0;
             $message="添加失败";
         }
    	  $merchandise->_discount=$discount;
    	  $merchandise->_ctype=$ctype;
    	  $merchandise->_photos=$photos;
          return MessageUtil::getMessage($messageSucess,$messageType,$message,$merchandise);
	}
	/**
	 * 更新商品信息
	 * @param  $info
	 */
	public function update($info) {
		  $merchandise = new Merchandise();
		  $ctype = new Ctype();
	      $discount=new Discount();
	      $merchandiseobj= json_decode($info);
          $ctypeobj=$merchandiseobj->_ctype;
          $photoobjs=$merchandiseobj->_photos;
          $discountobj=$merchandiseobj->_discount;
          MinnUtil::obj2Map($merchandiseobj,$merchandise);
          MinnUtil::obj2Map($ctypeobj,$ctype);
          MinnUtil::obj2Map($discountobj,$discount);
          $keyArr=array("date"=>"createDate");
          $photos=array();
		 try{
         	 $conn=DBUtil::getConnection();
         	 @mysql_query('START TRANSACTION') or die(mysql_error());
         	 
         	 $msql="update merchandise set category_id='$merchandise->category_id',descript='$merchandise->descript',
         	  downTime=date_format('$merchandise->downTime','%Y-%c-%d %H:%i:%s'),name='$merchandise->name',
         	  pcount=$merchandise->pcount,price=$merchandise->price,upTime=date_format('$merchandise->upTime','%Y-%c-%d %H:%i:%s')
         	   where id='$merchandise->id'";
         	 @mysql_query($msql,$conn) or die(mysql_error());
         	 $dsql="update discount set percend=$discount->percend where id=$discount->id";
         	 @mysql_query($dsql,$conn) or die(mysql_error());
         	  
		     for($j=0;$j<count($photoobjs);$j++){
    	     	$photo=new Photo();
    	     	 MinnUtil::obj2Map($photoobjs[$j],$photo);
    	     	 $psql="update mcd_phone set imgname='$photo->imgname',level1type='$photo->level1type',level2type='$photo->level2type',
    	     	    where id='$photo->id'";
    	     	 @mysql_query($psql,$conn) or die(@mysql_error());
    	     	 array_push($photos,$photo);
    	     }
         	  
         	 @mysql_query('COMMIT') or die(mysql_error());
		     $messageSucess=1;
          	 $message="更新成功";
          	 DBUtil::closeConn($conn);
         }catch(Exception $e){
         	 @mysql_query('ROLLBACK');
         	 $messageSucess=0;
             $message="更新失败";
         }
    	  $merchandise->_discount=$discount;
    	  $merchandise->_ctype=$ctype;
    	  $merchandise->_photos=$photos;
          return MessageUtil::getMessage($messageSucess,$messageType,$message,$merchandise);
	}
	/**
	 * 删除商品
	 * @param  $info
	 */
	public function delete($info) {
		
	}
	/**
	 * 查找商品类型
	 *  //'[{label:"root1",children:[{label:"Mail Box",children:
	 * [{label:"test1",data:1},{label:"test2",data:1}]},
	 * {label:"Mail Box1",children:[{label:"test2",data:1}]}]}]';
	 * <>
					<menuitem label="系统管理" data="0">
						<menuitem label="操作员管理" url="mvc/view/manager/operator/component/OperatorMngPanel.swf"/>
						<menuitem label="用户管理" url="mvc/view/manager/user/component/UserMngPanel.swf"/>
						<menuitem label="付款方式管理" url="mvc/view/manager/payway/component/PaywayMngPanel.swf"/>
					</menuitem>
					<menuitem label="商品管理" data="top">
						<menuitem label="商品管理" url="mvc/view/manager/merchandise/component/MerchandiseMngPanel.swf"/>
						<menuitem label="商品类型管理" url="mvc/view/manager/ctype/component/CtypeMngPanel.swf"/>
					</menuitem>
					<menuitem label="订单管理" data="top">
						<menuitem label="查看订单" url="mvc/view/manager/order/component/OrderMngPanel.swf"/>
					</menuitem>
				</>;
	 * @param  $condition
	 */
	public function queryCtypeTree() {
		$conn=DBUtil::getConnection();
		$sql0="select * from category ct where ct.category=0";
		 try{
			$result0=@mysql_query($sql0,$conn) or die(@mysql_error());
			$message="<node name='根菜单'>";
			  while ($row0 =@mysql_fetch_array($result0)) {
			  		$message.="<node name='".$row0['name']."' id='".$row0['id']."' pid='".$row0['pid']."' category='".$row0['cateogory']."' 
			  		 descript='".$row0['descript']."'>";
			  		 $sql1="select * from category ct where ct.category=1 and ct.pid='".$row0['id']."'";
			  		 $result1=@mysql_query($sql1,$conn);
			   		   while ($row1 =@mysql_fetch_array($result1)) {
			  		     	$message.="<node name='".$row1['name']."' id='".$row1['id']."' pid='".$row1['pid']."' category='".$row1['cateogory']."' 
			  		     	descript='".$row1['descript']."'>";
			  		     	//以后或者有用
	//		  		       $sql2="select * from category ct where ct.category=2 and ct.pid='".$row1['id']."'";
	//		  		        $result2=@mysql_query($sql2,$conn);
	//		  		        while ($row2 =@mysql_fetch_array($result2)) {
	//		  		     	   $message.="<node label='".$row2['name']." 'id='".$row2['id']."'/>";
	//		  		   	
	//		  		        }
			  		     $message.="</node>";
			  		   }
			  		 $message.="</node>";
			  }
			  $message.="</node>";
			  $messageSucess=3;
	     }catch(Exception $e){
			  $messageSucess=0;
			  $message="查询数据失败";
	     }
//		  echo $message;
//		  $message = json_encode($arr); 
		return MessageUtil::getMessage($messageSucess,'array',$message);
	}
	/**
	 * 查找商品
	 * @param  $condition
	 */
	public function query($condition) {
		
		$re=json_decode($condition);
		$type_name= $re->type_name;
		$pageIndex= $re->pageIndex;
		$pageSize= $re->pageSize;
		$recordCount=$re->recordCount;
		$rowStart=$pageIndex*$pageSize;
		try{
			$conn=DBUtil::getConnection();
			if($recordCount==-1){
			  $recordCount=parent::getTotalCount($conn,'merchandise');
			}
			$msql="select m.*,(select opr_name_ch from operator where id=m.creator) as _creatorName
	         from merchandise m,category c where c.id=m.category_id and m.name like '%$type_name%'  order by createDate desc limit $rowStart,$pageSize";
			
	         $result=@mysql_query($msql,$conn) or die(@mysql_error());
	          
			 $arr=array();
			  while ($row =@mysql_fetch_array($result)) {
			  		 $v=new Merchandise();	
			  		 $v->id=$row['id'];
			  		 $v->name=$row['name'];
			  		 $v->createDate=$row['createDate'];
			  		 $v->category_id=$row['category_id'];
			  		 $v->creator=$row['creator'];
			  		 $v->descript=$row['descript'];
			  		 $v->downTime=$row['downTime'];
			  		 $v->pcount=$row['pcount'];
			  		 $v->_creatorName=$row['_creatorName'];
			  		 $v->price=$row['price'];
			  		 $v->upTime=$row['upTime'];
			  		 
			  		 $csql="select ct.*,(select opr_name_ch from operator where id=ct.creator) as _creatorName 
						,(case ct.category
                				when  0 then '顶级菜单' 
                				when  1 then '一级菜单'
                				when  2 then '二级菜单'
             				end  ) as _categoryName ,
            			 if(ct.pid is not null,(select ct0.name from category ct0 where ct0.id=ct.pid),'顶级菜单')
        				 as _parentName from category ct where  ct.id='".$row['category_id']."'";
			  		 $resultc=@mysql_query($csql,$conn) or die(@mysql_error());
			  		 $ctype = new Ctype();
			  		  while ($rowc =@mysql_fetch_array($resultc)) {
			  		  	 $ctype->id=$rowc['id'];
			  		  	 $ctype->category=$rowc['category'];
			  		  	 $ctype->creator=$rowc['creator'];
			  		  	 $ctype->descript=$rowc['descript'];
			  		  	 $ctype->name=$rowc['name'];
			  		  	 $ctype->pid=$rowc['pid'];
			  		  	 $ctype->_categoryName=$rowc['_categoryName'];
			  		  	 $ctype->_creatorName=$rowc['_creatorName']; 
			  		  	 $ctype->_parentName=$rowc['_parentName'];
			  		  }
			  		 $v->_ctype=$ctype;
			  		 
			  		 $dsql="select * from discount where dc_id='".$row['id']."' and type=2";
			  		 $resultd=@mysql_query($dsql,$conn) or die(@mysql_error());
			  		 $discount=new Discount();
			  		  while ($rowd =@mysql_fetch_array($resultd)) {
			  		  	$discount->id=$rowd['id'];
			  		  	$discount->createDate=$rowd['createDate'];
			  		  	$discount->creator=$rowd['creator'];
			  		  	$discount->dc_id=$rowd['dc_id'];
			  		  	$discount->percend=$rowd['percend'];
			  		  	$discount->type=$rowd['type'];
			  		  }
			  		 $v->_discount=$discount;
			  		 $photos=array();
			  		 $psql="select * from mcd_phone where mcd_id='".$row['id']."'";  
			  		 $resultp=@mysql_query($psql,$conn) or die(@mysql_error());
			  		 $v->_imgcount=count($resultp);
			  		 while ($rowp =@mysql_fetch_array($resultp)) {
			  		 	$photo=new Photo();
			  		 	$photo->createDate=$rowp['createDate'];
			  		 	$photo->creator=$rowp['creator'];
			  		 	$photo->id=$rowp['id'];
			  		 	$photo->imgname=$rowp['imgname'];
			  		 	$photo->imgpath=$rowp['imgpath'];
			  		 	$photo->level1type=$rowp['level1type'];
			  		 	$photo->level2type=$rowp['level2type'];
			  		 	$photo->phone_order=$rowp['phone_order'];
			  		 	$photo->sourcename=$rowp['sourcename'];
			  		 		
			  		 	 array_push($photos,$photo);
			  		 }
			  		
			  		$v->_photos=$photos; 
			  	  array_push($arr,$v);
			  }
			  $message = json_encode($arr); 
			  $messageSucess=3;
		}catch(Exception $e){
			  $messageSucess=0;
			  $message="查询数据失败";
	     }
		 
		return MessageUtil::getMessage($messageSucess,'array',$message,$recordCount);
	}

	

}
?>