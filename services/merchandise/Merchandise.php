<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
class Merchandise {
 	var $_sid;
 	var $id;
    var $name;
    var $descript;
    var $price;
    var $category_id;
    var $creator;
    var $createDate;
    var $pcount;
    var $upTime;
    var $downTime;
    var $weight;
    var $isfirst;
    var $_photos;
    var $_discount;
    var $_ctype;
    var $_color;
    var $_imgcount;
    var $_creatorName;
    var $otherpath;
    // explicit actionscript package
    var $_explicitType = "mvc.model.merchandise.vo.Merchandise";
    }

class Discount {
 	var $_sid;
 	var $id;
 	var $type;
    var $dc_id;
    var $creator;
    var $createDate;
    var $percend;
    var $_color;
    // explicit actionscript package
    var $_explicitType = "mvc.model.merchandise.vo.Merchandise";
    }      
?>