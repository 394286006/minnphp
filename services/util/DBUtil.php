<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
class DBUtil {
	
	function __construct(){
//		DBUtil::initDBUtil();

	}
	public static function initDBUtil() {
		
		 global $dbpool,$host,$name,$pwd,$db,$size;
		$dbpool = array ();
		$host = "localhost:3306";
		$name = "root";
		$pwd = "123456";
		$db = "minn";
//		$host = "localhost:3306";
//		$name = "a0210095735";
//		$pwd = "31392140";
//		$db = "a0210095735";
		$size=3;
		
			for($i = 0; $i < $size; $i ++) {
				$conn=DBUtil::connection();
				array_push ( $dbpool, $conn );
			}
	}
	
	public final static function getConnection() {
		try{
		if(!isset($GLOBALS['dbpool'])){
			DBUtil::initDBUtil();
		}
		$c=count($GLOBALS['dbpool'] );
		if($c>0){
		$con=array_pop ( $GLOBALS ['dbpool'] );
		$re=@mysql_query ( "select 1=1 ",$con );
//		 echo "mysql connection:".$re;
			if ($re=='')
			  $con=  DBUtil::getConnection();
		}
		else {
			$con=DBUtil::connection();
		}
		}catch(Exception $e){
			
		}
		return $con;
	}
	
	public final static function getPools() {
		
		return count ( $GLOBALS ['dbpool'] );
	}
	
	public final static function closeConn($conn){
		$c=count($GLOBALS ['dbpool'] );
		if($c<$GLOBALS ['size'] )
		array_push ( $GLOBALS ['dbpool'], $conn );
		else
		mysql_close($conn);
	}
	
	private final static function connection(){
		try {
		$conn = @mysql_connect ( trim ( $GLOBALS ['host'] ), trim ( $GLOBALS ['name'] ), trim ( $GLOBALS ['pwd'] ) ) or die ( @mysql_error () );
		@mysql_select_db ( trim ( $GLOBALS ['db'] ), $conn ) or die ( @mysql_error () );
		@mysql_query ( "set names utf8;",$conn ) or die ( @mysql_error () );
		} catch ( ErrorException $e ) {
			//$isExit="0";
			echo $e;
			die ( mysql_error () );
		}
	    return $conn;
	}
}

?>