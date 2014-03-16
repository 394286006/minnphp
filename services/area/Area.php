<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
class Province {
 	var $_sid;
	var $id;
	var $name;
	var $nid;
	var $creator;
	var $createDate;
	var $_creatorName;
	var $_color;
	var $_citys;
    // explicit actionscript package
    var $_explicitType = "mvc.model.city.vo.Province";
    }
    
  class City {
    var $_sid;
	var $id;
	var $name;
	var $nid;
	var $p_id;
	var $flag;
	var $creator;
	var $createDate;
	var $_creatorName;
	var $_color;
	var $_towns;
    var $_explicitType = "mvc.model.city.vo.City";
    }
   class Town {
    var $_sid;
	var $id;
	var $name;
	var $nid;
	var $c_id;
	var $flag;
	var $creator;
	var $createDate;
	var $_creatorName;
	var $_color;
    var $_explicitType = "mvc.model.city.vo.Town";
    } 
    
class ProvinceMenu {
	var $id;
	var $name;
	var $nid;
	var $children;
    }
    
  class CityMenu {
	var $id;
	var $name;
	var $nid;
	var $p_id;
	var $flag;
	var $children;
    }
   class TownMenu {
	var $id;
	var $name;
	var $flag;
	var $nid;
	var $c_id;
    } 
        
    
    
?>