<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
include '../../setting.php';
require_once 'Order.php';
require_once APPROOT.'front/product/Product.php';
require_once APPROOT.'order/IOrderService.php';
require_once APPROOT.'util/MinnUtil.php'; 
require_once APPROOT.'base/Base.php';
require_once APPROOT.'util/MessageUtil.php';
require_once APPROOT.'base/JSON.php';
 @session_start();
class OrderService extends Base implements IOrderService{
	
	
	
	/**
	 * 添加用户（用户注册）
	 * @param $login
	 */

	public function add($info){
		  $order = new Order();
	      $orderaddress=new OrderAddress();
          $orderobj= json_decode($info);
          MinnUtil::obj2Map($orderobj,$order);
        if($_SESSION['securitykey']==$order->_sid){ 
         
          $orderaddessobjs=$order->_orderAddress;
           MinnUtil::obj2Map($orderaddessobjs,$orderaddress);
          $productsobj=$order->_products;
        
          $keyArr=array("date"=>"createDate");

         try{
         	 $conn=DBUtil::getConnection();
         	 @mysql_query('START TRANSACTION',$conn) or die(mysql_error());
         	 $orderaddress->id=time().self::getSRM();
         	 $msql=MinnUtil::buildInserSql("orderaddress",$orderaddress,$keyArr);
    	     @mysql_query($msql,$conn) or die(@mysql_error());
    	     $order->oa_id=$orderaddress->id;
    	     $order->id=time().self::getSRM();
    	     $dsql=MinnUtil::buildInserSql("uorder",$order,$keyArr);
    	     @mysql_query($dsql,$conn) or die(@mysql_error());
    	     
    	     for($j=0;$j<count($productsobj);$j++){
    	     	$product=new Product();
    	     	  MinnUtil::obj2Map($productsobj[$j],$product);
	    	      $psql="insert into merchandise_order(o_id,m_id,qty) values('$order->id','$product->id',$product->_qty)";
	    	      @mysql_query($psql,$conn) or die(@mysql_error());
    	     	
    	     }
    	     
    	     @mysql_query('COMMIT',$conn) or die(mysql_error());
    		 $messageSucess=1;
          	 $message="添加成功";
          	  DBUtil::closeConn($conn);
         }catch(Exception $e){
         	 @mysql_query('ROLLBACK');
         	 $messageSucess=0;
             $message="添加失败";
             echo $e;
         }
        }else{
        	$messageSucess=0;
	        $message='非法操作！';
        }
          return MessageUtil::getMessage($messageSucess,$messageType,$message);
	}


	/**
	 * 查找用户
	 * @param  $condition
	 */
	public function query($condition) {
	
		$re=json_decode($condition);
		$_sid=$re->_sid;
		$u_id= $re->id;
		$ispay=$re->ispay;
		$pageIndex= $re->pageIndex;
		$pageSize= $re->pageSize;
		$recordCount=$re->recordCount;
		$rowStart=$pageIndex*$pageSize;
		
		
		$sql="select ct.* from uorder ct where ct.creator='$u_id' ";// where ct.id='$u_id'";
		 if($ispay==0){
		 	$sql.=" and (ct.ispay=0 or ct.ispay is null)";
		 }
	     if($ispay==1){
		 		$sql.=" and ct.ispay=1 ";
		  }
		  $sql.=" order by createDate desc limit $rowStart,$pageSize";
	  if($_sid==$_SESSION['securitykey']){
		try{
		 $conn=DBUtil::getConnection();
         $result=@mysql_query($sql,$conn) or die(@mysql_error());
//		if($recordCount==-1){
//		  $recordCount=parent::getTotalCount($conn,'uorder');
//		}
		 $arr=array();
		  while ($row =@mysql_fetch_array($result)) {
		  		 $ct=new Order();	
		  		 $ct->id=$row['id'];
		  		 $ct->name=$row['name'];
		  		 $ct->createDate=$row['createDate'];
		  		 $ct->getway=$row['getway'];
		  		 $ct->creator=$row['creator'];
		  		 $ct->totalmoney=$row['totalmoney'];
		  		 $ct->totalqty=$row['totalqty'];
		  		 $ct->totalweight=$row['totalweight'];
		  		 $ct->oa_id=$row['oa_id'];
		  		 $ct->body=$row['body'];
		  		 $ct->out_trade_no=$row['out_trade_no'];
		  		 $ct->subject=$row['subject'];
		  		 
		  		 $addresssql="select ct.* from orderaddress ct where ct.id='".$row['oa_id']."'";
		  		 $result1=@mysql_query($addresssql,$conn)  or die(@mysql_error());
		  		 $row1 =@mysql_fetch_array($result1);
		  		 $address=new OrderAddress();
		  		 $address->id=$row1['id'];  
		  		 $address->address=$row1['address'];  	
		  		 $address->code= $row1['code'];  
		  		 $address->email=$row1['email'];  
		  		 $address->phone=$row1['phone'];  
		  		 $address->receiveName=$row1['receiveName'];
		  		 $ct->_orderAddress=$address;
		  		 $psql="select p.*,o.qty as _qty from merchandise p,merchandise_order o where p.id=o.m_id and o.o_id='".$row['id']."'";
		  		 $result2=@mysql_query($psql,$conn)  or die(@mysql_error());
		  		 $products=array();
		  		 while ($row2 =@mysql_fetch_array($result2)) {
		  		       $p=new Product();	
		  		       $p->id=$row2['id'];
		  		       $p->category_id=$row2['category_id'];
		  		       $p->categoryName=$row2['categoryName'];
		  		       $p->descript=$row2['descript'];
		  		       $p->downTime=$row2['downTime'];
		  		       $p->name=$row2['name'];
		  		       $p->paent_category_id=$row2['paent_category_id'];
		  		       $p->parentCategoryName=$row2['parentCategoryName'];
		  		       $p->pcount=$row2['pcount'];
		  		       $p->price=$row2['price'];
		  		       $p->upTime=$row2['upTime'];
		  		       $p->weight=$row2['weight'];
		  		       $p->_qty=$row2['_qty'];
		  		       array_push($products,$p);
		  		 }
		  		 $recordCount=count($ct);
		  		 $ct->_products=$products;
		  		 array_push($arr,$ct);
		  }
		  $message = json_encode($arr); 
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
	 * 更新用户信息
	 * @param  $info
	 */
	public function update($info) {
		$orderobj= json_decode($info);
         $v = new Order(); 
//		 MinnUtil::josonToMap($info,$v);
         MinnUtil::obj2Map($orderobj,$v);
		  $messageSucess=1;
          $message="";
//		if(true){ 
		if($_SESSION['securitykey']==$v->_sid){ 
		try{
			 $orderaddress=new OrderAddress();
			 $orderaddessobjs=$v->_orderAddress;
		     $conn=DBUtil::getConnection();
		     @mysql_query('START TRANSACTION',$conn) or die(@mysql_error());
		    
	         MinnUtil::obj2Map($orderaddessobjs,$orderaddress);
		     $upordersql="update orderaddress set address='$orderaddress->address',code='$orderaddress->code'
		                  ,email='$orderaddress->email',phone='$orderaddress->phone'
		                  ,receiveName='$orderaddress->receiveName'
		                  where id='$orderaddress->id'";
		     if($v->totalqty=='')
		             $upordersql="delete from orderaddress where id='$orderaddress->id'"; 
		     @mysql_query($upordersql,$conn) or die(@mysql_error());
		     
		     $delsql="delete from merchandise_order where o_id='$v->id'";
		     @mysql_query($delsql,$conn) or die(@mysql_error());
		     
		     $productsobj=$v->_products;
		     for($j=0;$j<count($productsobj);$j++){
    	     	  $product=new Product();
    	     	  MinnUtil::obj2Map($productsobj[$j],$product);
    	     	  if($product->id!=''){
		    	      $psql="insert into merchandise_order(o_id,m_id,qty) values('$v->id','$product->id',$product->_qty)";
		    	      @mysql_query($psql,$conn) or die(@mysql_error());
    	     	  }
    	     }
    	       $usql="update uorder set getway=$v->getway,totalmoney=$v->totalmoney,totalweight=$v->totalweight
		       ,totalqty=$v->totalqty  where id='$v->id'";
		       
    	      if($v->totalqty=='')
		     	$usql="delete from uorder where id='$v->id'";
		   
		     
		     @mysql_query($usql,$conn) or die(@mysql_error());
    	     
    	     @mysql_query('COMMIT',$conn) or die(@mysql_error());
          	 $messageSucess=1;
          	 $message="更新成功";
             DBUtil::closeConn($conn);
		  }catch(Exception $e){
         	 @mysql_query('ROLLBACK');
         	 $messageSucess=0;
             $message="更新失败";
         }
		}else{
			$messageSucess=0;
	        $message='非法操作！';
		}
		 return MessageUtil::getMessage($messageSucess,$v->_explicitType,$message);
	}
	/**
	 * 删除用户
	 * @param  $info
	 */
	public function delete($info) {
		
		$v=json_decode($info); 
		$vid= $v->id;
		if($_SESSION['securitykey']==$v->_sid){ 
		$sql="delete from uorder where id='$vid'";
//		echo $sql;
		try{
            $conn=DBUtil::getConnection();
		    $result=@mysql_query($sql,$conn) or die(@mysql_error());
//		    echo 'dddddddddddd'.$result;
          	$messageSucess=1;
          	$message="删除成功";
            DBUtil::closeConn($conn);
		 }catch(Exception $e){
         	 $messageSucess=0;
             $message="删除失败";
         }
		}else{
			$messageSucess=0;
	        $message='非法操作！';
		}
		   
        
          return MessageUtil::getMessage($messageSucess,$messageType,$message);
	}

}
?>