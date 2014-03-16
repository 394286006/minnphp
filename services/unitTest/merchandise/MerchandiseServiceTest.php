<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
require_once 'merchandise/MerchandiseService.php';
require_once 'base/JSON.php';


echo '*********************Test CtypeService.php start **************************<br> \n';
		//模拟客户端传过来的字符处
		$ctype='{"descript":"嘎登","pid":"","createDate":"2010-11-27 22:48:09","creator":"","name":"阿发","color":1,"id":"","category":"0","_sid":"","_creatorName":""}';
		$obj=new MerchandiseService();
//	    $obj->add($ctype);
//        $obj->query('{"pageSize":3,"pageIndex":0,"recordCount":-1,"type_name":""}');
       // $obj->update('{"descript":"嘎登","pid":null,"createDate":"2010-12-25 07:52:14","creator":null,"name":"类别3","color":2,"id":"1293201506c8c3924a3385b6c14b4420f557b60608","category":"0","_sid":null,"_creatorName":null}');
       //查找商品类型'[{label:"root1",children:[{label:"Mail Box",children:[{label:"test1",data:1},{label:"test2",data:1}]},{label:"Mail Box1",children:[{label:"test2",data:1}]}]}]';
//       $obj->queryCtype();
   $addstr='{"_imgcount":"1","_photos":[{"phone_order":0,"level2type":"","imgname":"时间","imgpath":"_20101230201358.jpg","creator":"","level1type":"jpg","id":"","_sid":"","sourcename":"时间.jpg","mcd_id":"","createDate":""}],"_color":1,"name":"12","creator":"","_discount":null,"upTime":"2010-12-03  00:00:00","downTime":"","id":"","_ctype":{"_creatorName":"","createDate":"","color":0,"pid":"129333846789a5e09627dd18e1e334297679721859","creator":"","descript":"","id":"129333851733f9135eb5c0ee9c4d007167acf47439","category":"","_categoryName":"","name":"12","_parentName":"11","_sid":""},"price":"21","category_id":"129333851733f9135eb5c0ee9c4d007167acf47439","_sid":"","pcount":"12","descript":"12121212","createDate":""}';
//		$obj->add($addstr);
//		 $merchandise = new Merchandise();
//		   $ctype = new Ctype();
   $updastr='{"_imgcount":"1","_photos":[{"phone_order":0,"level1type":"jpg","sourcename":"图片名称:123456","imgname":"通行管理-用例图","id":"129370764775ef1021ee69eb5414f2103193d4c8dc","creator":null,"_sid":null,"level2type":"图片名称:123456","imgpath":"_20101230191407.jpg","createDate":"2010-12-30 19:14:07","mcd_id":"12937074513a1051d7bc1590ef30337746a52506b9"}],"_color":0,"name":"12","creator":null,"_discount":{"type":null,"_explicitType":"mvc.model.merchandise.vo.Merchandise","id":null,"percend":null,"creator":null,"_sid":null,"createDate":null,"_color":null,"dc_id":null},"upTime":"2010-12-02  00:00:00","downTime":"2010-12-15  00:00:00","id":"12937074513a1051d7bc1590ef30337746a52506b9","_ctype":{"_creatorName":"","createDate":"","color":0,"pid":"129333846789a5e09627dd18e1e334297679721859","creator":"","descript":"","id":"129333851733f9135eb5c0ee9c4d007167acf47439","category":"","_categoryName":"","name":"12","_parentName":"11","_sid":""},"price":"123.00","category_id":"129333851733f9135eb5c0ee9c4d007167acf47439","_sid":null,"pcount":"13","descript":"1321","createDate":"2010-12-30 19:10:51"}';
  
//   $obj->update($updastr);
//   $obj->generatorJsonMenu();

   $firstpage='[{"imgpath":"_20110122160309.JPG","mcd_id":"1295683447eff7451f28530f1defbd5e78bb67a742","phone_order":0,"createDate":"2011-01-22 16:04:07","level1type":"JPG","level2type":"JPG","sourcename":null,"imgname":"IMG_4462","creator":null,"id":"12956834474b52ddb7a0b6ac8553536d004c895b59","_sid":null}]';
   $obj->firstPageSet($firstpage);
   echo '<br>*********************Test CtypeService.php end **************************';
?>