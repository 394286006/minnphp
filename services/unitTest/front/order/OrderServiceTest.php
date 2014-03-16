<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
require_once 'front/order/OrderService.php';
require_once 'base/JSON.php';


echo '*********************Test CtypeService.php start **************************<br> \n';
		//模拟客户端传过来的字符处
		$orderstr='{"pw_id":"","getway":2,"totalmoney":5,"totalweight":19.4,"ispay":0,"totalqty":2,"_orderAddress":{"email":"123","address":"广东省清远市黄坑","creator":"","createDate":"","id":"","phone":"23","code":"123"},"_products":[{"upTime":"2010-12-08","downTime":"2010-12-18","_discount":null,"_photos":["_20101230210301.jpg","_20101230210324.jpg"],"descript":"13131313231123","qty":1,"price":"3.00","category_id":"129333851733f9135eb5c0ee9c4d007167acf47439","categoryName":"12","weight":"17.00","id":"1293714207d22050ea937ee457eeba22953aeea057","parent_category_id":"129333846789a5e09627dd18e1e334297679721859","name":"23","parentCategoryName":"11","pcount":"123"},{"upTime":"2010-12-06","downTime":"2010-12-25","_discount":"5","_photos":["_20101230210754.jpg"],"descript":"232424234","qty":1,"price":"2.00","category_id":"129333851733f9135eb5c0ee9c4d007167acf47439","categoryName":"12","weight":"2.40","id":"12937145016a61d423d02a1c56250dc23ae7ff12f3","parent_category_id":"129333846789a5e09627dd18e1e334297679721859","name":"4","parentCategoryName":"11","pcount":"3"}],"createDate":"","flag":0,"creator":"","id":"","_sid":""}';
		$obj=new OrderService();
//		$obj->add($orderstr);
		$upstr='{"_sid":"ddad3cfdf305d211b2600645e650df8d","pw_id":null,"getway":2,"totalmoney":4,"createDate":"2011-02-14 21:07:42","price":0,"totalweight":46,"flag":0,"ispay":0,"totalqty":2,"oa_id":"12976888623ca9c467df2542b0657483bb28cbe281","receive":0,"out_trade_no":"minn20110214210742","buyeremail":"","_creatorName":null,"body":null,"_orderAddress":{"email":"234","receiveName":"323","address":"广东省广州市天河区234","creator":"","createDate":"","id":"12976888623ca9c467df2542b0657483bb28cbe281","code":"234","phone":"234"},"_products":[{"name":"23","category_id":"1295967629ef48d3686e28da74fedb0773226e3b58","categoryName":null,"price":"2.00","parent_category_id":"","id":"1296779591cb86ee3218ac933fe2cb5540211195d6","pcount":"123","descript":"213123","upTime":"2011-02-08","parentCategoryName":null,"downTime":"2011-02-12","weight":"23.00","_qty":2,"_discount":null,"_photos":null}],"creator":"1296143891674d8d5f7f53691ee5ccf2d6095602da","subject":null,"id":"1297688862190b12368f262b0aa584cd9101ecc07c"}';
		$obj->update($upstr);
   echo '<br>*********************Test CtypeService.php end **************************';
?>