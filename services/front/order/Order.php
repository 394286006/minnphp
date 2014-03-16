<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
class Order {
   var $_sid;
   var $id;
   var $pw_id;
   var $creator;
   var $createDate;
   var $getway;
   var $totalmoney;
   var $totalweight;  
   var $flag;
   var $ispay;
   var $totalqty;
   var $oa_id;
   var $receive;
   var $out_trade_no;
   var $subject;
   var $body;

   var $_creatorName;
   var $_orderAddress;
   var $_products;
    // explicit actionscript package
    var $_explicitType = "mvc.model.order.vo.Order";
    }
class OrderAddress {
     var $id;
	 var $address;
	 var $phone;
	 var $email;
	 var $code;
	 var $receiveName;
//	 var $creator;
//	 var $createDate;
    // explicit actionscript package
    var $_explicitType = "mvc.model.order.vo.OrderAddress";
    }

class Merchandise_Order{
	var $o_id;
	var $m_id;    
}
    
?>