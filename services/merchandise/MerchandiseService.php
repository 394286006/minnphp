<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
include '../setting.php';
require_once 'Merchandise.php';
require_once APPROOT.'ctype/Ctype.php';
require_once APPROOT.'photo/Photo.php';
require_once APPROOT.'front/product/Product.php';
require_once 'IMerchandiseService.php';
require_once APPROOT.'util/MinnUtil.php'; 
require_once APPROOT.'base/Base.php';
require_once APPROOT.'util/MessageUtil.php';
require_once APPROOT.'base/JSON.php';
@session_start();
class MerchandiseService extends Base implements IMerchandiseService{
	
	
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
          // if($merchandise->_sid==$_SESSION['securitykey']){
        if($merchandise->_sid!=''){
         try{
         	 $conn=DBUtil::getConnection();
         	 @mysql_query('START TRANSACTION',$conn) or die(mysql_error());
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
    	     	 if( $photo->id==''){
	    	     	 $photo->id=time().self::getSRM();
	    	     	 $psql=MinnUtil::buildInserSql("mcd_phone",$photo,$keyArr);
	    	     	 @mysql_query($psql,$conn) or die(@mysql_error());
    	     	 }
    	     	 array_push($photos,$photo);
    	     }
    	     
    	     @mysql_query('COMMIT',$conn) or die(mysql_error());
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
            }else{
         	$messageSucess=0;
	        $message='非法操作！';
         }
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
       // if($merchandise->_sid==$_SESSION['securitykey']){ 
           if($merchandise->_sid!=''){   
		 try{
         	 $conn=DBUtil::getConnection();
         	 @mysql_query('START TRANSACTION',$conn) or die(@mysql_error());
         	 
         	 $msql="update merchandise set category_id='$merchandise->category_id',descript='$merchandise->descript',weight='$merchandise->weight',otherpath='$merchandise->otherpath',";
         	 if($merchandise->downTime!='')
         	  $msql.="downTime=date_format('$merchandise->downTime','%Y-%c-%d %H:%i:%s'),";
         	 if($merchandise->upTime!='')
         	  $msql.="upTime=date_format('$merchandise->upTime','%Y-%c-%d %H:%i:%s'),"; 
         	  $msql.="name='$merchandise->name',
         	  pcount=$merchandise->pcount,price=$merchandise->price 
         	   where id='$merchandise->id'";
         	 @mysql_query($msql,$conn) or die(mysql_error());
         	  $dsql='';
         	 if($discount->id=='')
         	 {
         	 	$discount->dc_id=$merchandise->id;
         	 	 $discount->id=time().self::getSRM();
    	        $dsql=MinnUtil::buildInserSql("discount",$discount,$keyArr);
         	   
         	 }else{
         	 	 $dsql="update discount set percend=$discount->percend where id='$discount->id'";
         	 }
         	 @mysql_query($dsql,$conn) or die(@mysql_error());
         	  
		     for($j=0;$j<count($photoobjs);$j++){
    	     	$photo=new Photo();
    	     	 MinnUtil::obj2Map($photoobjs[$j],$photo);
    	     	 $psql='';
    	     	 if($photo->id==''){
    	     	 	$photo->id=time().self::getSRM();
	    	     	$psql=MinnUtil::buildInserSql("mcd_phone",$photo,$keyArr);
    	     	 }else{
    	     	    $psql="update mcd_phone set imgname='$photo->imgname',level1type='$photo->level1type',level2type='$photo->level2type',descript='$photo->descript'
    	     	    where id='$photo->id'";
    	     	 }
    	     	 @mysql_query($psql,$conn) or die(@mysql_error());
    	     	 array_push($photos,$photo);
    	     }
         	  
         	 @mysql_query('COMMIT',$conn) or die(@mysql_error());
		     $messageSucess=1;
          	 $message="更新成功";
          	 DBUtil::closeConn($conn);
         }catch(Exception $e){
         	 @mysql_query('ROLLBACK');
         	 $messageSucess=0;
             $message="更新失败:".$e;
         }
    	  $merchandise->_discount=$discount;
    	  $merchandise->_ctype=$ctype;
    	  $merchandise->_photos=$photos;
         }else{
         	$messageSucess=0;
	        $message='非法操作！';
         }  
          return MessageUtil::getMessage($messageSucess,$messageType,$message,$merchandise);
	}
	/**
	 * 删除商品
	 * @param  $info
	 */
	public function delete($info) {
		 $merchandise = new Merchandise();
		 $merchandiseobj= json_decode($info);
         MinnUtil::obj2Map($merchandiseobj->_merchandise,$merchandise);
      //  if($merchandiseobj->_sid==$_SESSION['securitykey']){     
        	 if($merchandiseobj->_sid!=''){
		 try{
         	 $conn=DBUtil::getConnection();
         	 
         	 $msqlq="select * from mcd_phone where mcd_id='$merchandise->id'";
         	 $result=@mysql_query($msqlq,$conn) or die(@mysql_error());
         	 while ($row =@mysql_fetch_array($result)) {
         	   	   $imgpath1=$row['imgpath'];//.".".$row['level1type'];
         	   	   $imgpath2=$row['imgpath'];//.".".$row['level2type'];
         	   	   $uploadfilet = APPROOT.UPLOADDIR.IMGLEVEL1.$imgpath1; 
			      if(file_exists($uploadfilet)&&is_file($uploadfilet))
			      	 if(!unlink($uploadfilet))
		                      throw new Exception('删除失败!');
			      	 $uploadfilet = APPROOT.UPLOADDIR.IMGLEVEL2.$imgpath2; 
			      	 if(file_exists($uploadfilet)&&is_file($uploadfilet))
			      	 if(!unlink($uploadfilet))
		                      throw new Exception('删除失败!');
         	  }
         	 
         	 @mysql_query('START TRANSACTION') or die(@mysql_error());
         	 $dsql="delete from discount where dc_id='$merchandise->id'";
         	 @mysql_query($dsql,$conn) or die(@mysql_error());
         	 $psql="delete from mcd_phone where mcd_id='$merchandise->id'";
         	 @mysql_query($psql,$conn) or die(@mysql_error());
         	 $msql="delete from merchandise where id='$merchandise->id'";
         	 @mysql_query($msql,$conn) or die(@mysql_error());
		     @mysql_query('COMMIT') or die(@mysql_error());
		     $messageSucess=1;
          	 $message="删除成功";
          	 DBUtil::closeConn($conn);
         }catch(Exception $e){
         	 @mysql_query('ROLLBACK');
         	 $messageSucess=0;
             $message="删除失败";
         }
	 }else{
         	$messageSucess=0;
	        $message='非法操作！';
         }
          return MessageUtil::getMessage($messageSucess,$messageType,$message);
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
	public function queryCtypeTree($condition) {
		
		$re=json_decode($condition);
		$_sid=$re->_sid;
		//if($_sid==$_SESSION['securitykey']){
		if($_sid!=''){
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
		 }else{
         	$messageSucess=0;
	        $message='非法操作！';
         }
//		  echo $message;
//		  $message = json_encode($arr); 
		return MessageUtil::getMessage($messageSucess,'array',$message);
	}
//public function queryCtypeTree() {
//		$conn=DBUtil::getConnection();
//		$sql0="select * from category ct where ct.category=0";
//		 try{
//			$result0=@mysql_query($sql0,$conn) or die(@mysql_error());
////			$message="<node name='根菜单'>";
////			 $root=array();
////			 $menu=new CtypeMenu();
////			 $menu->name='根菜单';
//			  $menus=array();
//			
//			  while ($row0 =@mysql_fetch_array($result0)) {
////			  		$message.="<node name='".$row0['name']."' id='".$row0['id']."' pid='".$row0['pid']."' category='".$row0['cateogory']."' 
////			  		 descript='".$row0['descript']."'>";
//                     $menu1=new CtypeMenu();
//                     $menu1->name=$row0['name'];
//                     $menu1->id=$row0['id'];
//                     $menu1->pid=$row0['pid'];
//                     $menu1->category=$row0['cateogory'];
//                     $menu1->descript=$row0['descript'];
//			  		 $sql1="select * from category ct where ct.category=1 and ct.pid='".$row0['id']."'";
//			  		
//			  		 $result1=@mysql_query($sql1,$conn) or die(@mysql_error());
//			  		    $children=array();
//			   		while ($row1 =@mysql_fetch_array($result1)) {
////			  		     	$message.="<node name='".$row1['name']."' id='".$row1['id']."' pid='".$row1['pid']."' category='".$row1['cateogory']."' 
////			  		     	descript='".$row1['descript']."'>";
//	                     $menu2=new CtypeMenu();
//	                     $menu2->name=$row1['name'];
//	                     $menu2->id=$row1['id'];
//	                     $menu2->pid=$row1['pid'];
//	                     $menu2->category=$row1['cateogory'];
//	                     $menu2->descript=$row1['descript'];
//			  		     	//以后或者有用
//	//		  		       $sql2="select * from category ct where ct.category=2 and ct.pid='".$row1['id']."'";
//	//		  		        $result2=@mysql_query($sql2,$conn);
//	//		  		        while ($row2 =@mysql_fetch_array($result2)) {
//	//		  		     	   $message.="<node label='".$row2['name']." 'id='".$row2['id']."'/>";
//	//		  		   	
//	//		  		        }
//	
//			  		     array_push($children,$menu2);
//			  		}
//			  		if(count($children)>0)
//			  		  $menu1->children=$children;
//			  		  array_push($menus,$menu1);
//			  }
////			  $menu->children=$menus;
////			  array_push($root,$menu);
//			  $message = json_encode($menus); 
//			  $messageSucess=1;
//	     }catch(Exception $e){
//			  $messageSucess=0;
//			  $message="查询数据失败";
//	     }
////		  echo $message;
//		
//		return MessageUtil::getMessage($messageSucess,'array',$message);
//	}
	/**
	 * 查找商品
	 * @param  $condition
	 */
	public function query($condition) {
		
		$re=json_decode($condition);
		$_sid=$re->_sid;
		$type_name= $re->type_name;
		$type_maxcategory_id= $re->type_maxcategory_id;
		$type_category_id= $re->type_category_id;
		$pageIndex= $re->pageIndex;
		$pageSize= $re->pageSize;
		$recordCount=$re->recordCount;
		$rowStart=$pageIndex*$pageSize;
		//if($_sid==$_SESSION['securitykey']){
		if($_sid!=''){
		try{
			$conn=DBUtil::getConnection();
			if($recordCount==-1){
//			$msql="select count(m.id) c
//	         	from merchandise m,category c where c.id=m.category_id and m.name like '%$type_name%'";
//	            if($type_maxcategory_id!=''){
//	            	$msql.=" and m.category_id in(select c.id from category c where c.pid='$type_maxcategory_id' ";
//	            }
//			   if($type_category_id!=''){
//	            	$msql.=" and  c.id='$type_category_id' ";
//	            }
//	            if($type_maxcategory_id!=''){
//	            	$msql.=")";
//	            }
//			  $recordCount=0;
//			  $result=@mysql_query($msql,$conn);
//			  if($row =@mysql_fetch_array($result)) {
//			  	$recordCount=$row['c'];
//			  }
			}
			$msql="select m.*,(select opr_name_ch from operator where id=m.creator) as _creatorName,
			 (select count(1) from mcd_phone where mcd_id=m.id) as _imgcount
	         from merchandise m,category c where c.id=m.category_id and m.name like '%$type_name%'";
            if($type_maxcategory_id!=''){
            	$msql.=" and m.category_id in(select c.id from category c where c.pid='$type_maxcategory_id' ";
            }
		   if($type_category_id!=''){
            	$msql.=" and  c.id='$type_category_id' ";
            }
            if($type_maxcategory_id!=''){
            	$msql.=")";
            }
	        $msql.=" order by createDate desc limit $rowStart,$pageSize";
			
	         $result=@mysql_query($msql,$conn) or die(@mysql_error());
	          
			 $arr=array();
			  while ($row =@mysql_fetch_array($result)) {
			  		 $v=new Merchandise();	
			  		 $v->id=$row['id'];
			  		 $v->name=$row['name'];
			  		 $v->createDate=$row['createDate'];
			  		 $v->category_id=$row['category_id'];
			  		 $v->creator=$row['creator'];
			  		 $v->weight=$row['weight'];
			  		 $v->descript=$row['descript'];
			  		 $v->downTime=$row['downTime'];
			  		 $v->pcount=$row['pcount'];
			  		 $v->_creatorName=$row['_creatorName'];
			  		 $v->price=$row['price'];
			  		 $v->upTime=$row['upTime'];
			  		 $v->_imgcount=$row['_imgcount'];
			  		 $v->otherpath=$row['otherpath'];
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
			  		 while ($rowp =@mysql_fetch_array($resultp)) {
			  		 	$photo=new Photo();
			  		 	$photo->createDate=$rowp['createDate'];
			  		 	$photo->creator=$rowp['creator'];
			  		 	$photo->id=$rowp['id'];
			  		 	$photo->imgname=$rowp['imgname'];
			  		 	$photo->imgpath=$rowp['imgpath'];
			  		 	$photo->mcd_id=$rowp['mcd_id'];
			  		 	$photo->level1type=$rowp['level1type'];
			  		 	$photo->level2type=$rowp['level2type'];
			  		 	$photo->phone_order=$rowp['phone_order'];
			  		 	$photo->sourcename=$rowp['sourcename'];
			  		 	$photo->descript=$rowp['descript'];
			  		 		
			  		 	 array_push($photos,$photo);
			  		 }
			  		
			  		$v->_photos=$photos; 
			  	  array_push($arr,$v);
			  }
			   $recordCount=count($arr);
			  $message = json_encode($arr); 
			 
			  $messageSucess=3;
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
	 * 菜单保存模式
	 * [{"id":"1","lable":"tte","children": [
	 *                                         {"id":"01", "label": "teet", "url":"", "icon":""},
	 *                                         {"id":"02", "label": "te", "url":"", "icon":""}
     *                                       ]
     *   },{"id":2,"lable":"etet","children": [
	 *									  {"id":"01", "label": "s", "url":"", "icon":""},
	 *									  {"id":"02", "label": "sf", "url":"", "icon":""}
     *                                         ]
     *    ]
	 *
	 * @return unknown
	 */
	public function generatorJsonMenu($condition) {
	    
		$re=json_decode($condition);
		$_sid=$re->_sid;
		//if($_sid==$_SESSION['securitykey']){
		if($_sid!=''){
		$sql0="select * from category ct where ct.category=0";
		 try{
		 	 $conn=DBUtil::getConnection();
			$result0=@mysql_query($sql0,$conn) or die(@mysql_error());
		
			$message=array();
			$ctype=new CtypeMenu();
			  $ctype->name='';
		  	  $ctype->category='';
		  	  $ctype->id='';
		  	  $ctype->pid='';
			 array_push($message,$ctype);
			  while ($row0 =@mysql_fetch_array($result0)) {
			 
			  	 $ctype=new CtypeMenu();
			  	  $ctype->name=$row0['name'];
			  	  $ctype->category=$row0['cateogory'];
			  	  $ctype->id=$row0['id'];
			  	  $ctype->pid=$row0['pid'];
			  	  $ch=array();
			  		 $sql1="select * from category ct where ct.category=1 and ct.pid='".$row0['id']."'";
			  		 
			  		$ctype1=new CtypeMenu();
			  		$ctype1->name='';
			  	    $ctype1->category='';
			  	    $ctype1->id='';
			  	    $ctype1->pid='';
			  		array_push($ch,$ctype1);
			  		 $result1=@mysql_query($sql1,$conn);
			   		   while ($row1 =@mysql_fetch_array($result1)) {
			   		 		$ctype1=new CtypeMenu();
					  	    $ctype1->name=$row1['name'];
					  	    $ctype1->category=$row1['cateogory'];
					  	    $ctype1->id=$row1['id'];
					  	    $ctype1->pid=$row1['pid'];
			   		   	  array_push($ch,$ctype1);
			  		     	//以后或者有用
	//		  		       $sql2="select * from category ct where ct.category=2 and ct.pid='".$row1['id']."'";
	//		  		        $result2=@mysql_query($sql2,$conn);
	//		  		        while ($row2 =@mysql_fetch_array($result2)) {
	//		  		     	   $message.="<node label='".$row2['name']." 'id='".$row2['id']."'/>";
	//		  		   	
	//		  		        }
			  		    
			  		   }
			   $ctype->children=$ch;
			   array_push($message,$ctype);
			  }
			
			  $messageSucess=1;
	     }catch(Exception $e){
			  $messageSucess=0;
			  $message="生成菜单数据失败";
	     }            
        $wenjian = fopen(APPROOT.CTYPEMENU,'w');
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
	
	public function getFirstPageSet($condition){
	
			$re=json_decode($condition);
		$_sid=$re->_sid;
		//if($_sid==$_SESSION['securitykey']){
		if($_sid!=''){
			$sql0="select * from mcd_phone ct where ct.isfirst=1";
		 try{
		 	 $conn=DBUtil::getConnection();
			$result0=@mysql_query($sql0,$conn) or die(@mysql_error());
			$arr=array();
			  while ($rowp =@mysql_fetch_array($result0)) {
			  	$photo=new Photo();
	  		 	$photo->createDate=$rowp['createDate'];
	  		 	$photo->creator=$rowp['creator'];
	  		 	$photo->id=$rowp['id'];
	  		 	$photo->imgname=$rowp['imgname'];
	  		 	$photo->mcd_id=$rowp['mcd_id'];
	  		 	$photo->imgpath=$rowp['imgpath'];
	  		 	$photo->level1type=$rowp['level1type'];
	  		 	$photo->level2type=$rowp['level2type'];
	  		 	$photo->phone_order=$rowp['phone_order'];
	  		 	$photo->sourcename=$rowp['sourcename'];
	  		 		
	  		 	 array_push($arr,$photo);
			  }
			  $message= json_encode($arr); 
			$messageSucess=1;
	     }catch(Exception $e){
			  $messageSucess=0;
			  $message="查询首页设置数据失败!";
	     }        
	 }else{
         	$messageSucess=0;
	        $message='非法操作！';
         }    
        return MessageUtil::getMessage($messageSucess,'array',$message);   
	}
	
	public function firstPageSet($info) {
		
		 $re=json_decode($info);
		 $photoobjs=$re->photos;
		$_sid=$re->_sid;
		//if($_sid==$_SESSION['securitykey']){
		if($_sid!=''){
		 try{
		 	 $conn=DBUtil::getConnection();
		 	 $sql0="update mcd_phone set isfirst=0 ";
		 	 @mysql_query($sql0,$conn) or die(@mysql_error());
		 	 $arr=array();
		     for($j=0;$j<count($photoobjs);$j++){
         	   $photo=new Photo();
         	   MinnUtil::obj2Map($photoobjs[$j],$photo);
         	   $sql1="update mcd_phone set isfirst=1 where id='$photo->id'";
    	       @mysql_query($sql1,$conn) or die(@mysql_error());
    	       
    	      $msql="select m.*,(select opr_name_ch from operator where id=m.creator) as _creatorName,
    	      c.name as categoryName,c.pid as parent_category_id,
    	      (select name from category where id=c.pid) as parentCategoryName
    	      ,(select count(1) from mcd_phone where mcd_id=m.id) as _imgcount from merchandise m,category c
    	       where c.id=m.category_id and m.id='$photo->mcd_id'";
    	       
	          $result=@mysql_query($msql,$conn) or die(@mysql_error());
	          
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
                        $p->isfirst=$rowp['isfirst'];
			  		 	$p->imgpath=$rowp['imgpath'];
//			  		 	$p->level1type=$rowp['level1type'];
//			  		 	$p->level2type=$rowp['level2type'];
//			  		 	$p->phone_order=$rowp['phone_order'];
//			  		 	$p->sourcename=$rowp['sourcename'];
			  		 		
			  		 	 array_push($photos,$p);
			  		 }
			  		
			  		$v->_photos=$photos; 
			  	  array_push($arr,$v);
			  }
         	 }
         	   $message = json_encode($arr); 
             $wenjian = fopen(APPROOT.FIRSTPAGE,'w');
             if($wenjian){
                fwrite($wenjian,urlencode($message)); 
             }  
         	 $message="设置首页图片成功!";
			 $messageSucess=1;
			 DBUtil::closeConn($conn);
	     }catch(Exception $e){
			  $messageSucess=0;
			  $message="设置首页图片失败!";
	     }            
	     
	     }else{
         	$messageSucess=0;
	        $message='非法操作！';
         } 
       
        return MessageUtil::getMessage($messageSucess,'string',$message);   
	}
	
}
?>