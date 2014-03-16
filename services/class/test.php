
<script type="text/javascript">
<!--
 var tempurl=window.location.href;
  var i=tempurl.lastIndexOf("/");
   var turl=tempurl.substring(0,i);
   var i2=turl.lastIndexOf("/");
  var url=turl.substring(0,i2)+"/html/login.html";
  alert(i2);
  alert(url);
//-->
</script>
<?php
  //date_default_timezone_set("PRC");
 //$tt=new DateTime();
 //$rr=$tt.

 //$d=new DateTime();//date('Y-m-d H:i:s');
 $t=time();
// print_r($t);
$st = 1170288000; //  a timestamp

$dt = new DateTime("@$t+8 hours");
//print_r($dt->format('Y-m-d H:i:s'));
  print_r(rand(1,100));
  
  
  
?>