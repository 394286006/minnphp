<?php
/**
 * @author minn
 * @QQ 394286006
 * @email freemanfreelift@gmail.com
 *
 */
class Page{
  private $totalCount;
  private $totalPage;
  public $pageSize=3;

function getPage($curPage,$startPage,$endPage)
{
 // echo "in getPage:"+$curPage;
	$pageRange=3;
	$start=$startPage;
	$end=$endPage;
	//echo $endPage;
	if($curPage==$startPage)
	{
		$end=$startPage;
		$start=$startPage-$pageRange;
	}
	if($curPage==$endPage)
	{
		$start=$curPage;
		$end=$curPage + $pageRange;
		//echo $end;
		if($this->totalPage < $end)
		{
			$end=$this->totalPage;
		}
	}
	if($curPage==$this->totalPage)
	{
		$start=$startPage;
		$end=$this->totalPage;
	}
 if(($curPage==$startPage)&&($curPage==$endPage))
	  {
		  $start=$curPage-$pageRange;
		  $end=$curPage;
	  }

	  if($start==$end)
	  {
		  $start=$start-$pageRange;
	  }
		if($start<=1)
		{
			$start=1;
			if($this->totalPage<$pageRange)
				 $end=$this->totalPage;
			else
				$end=$pageRange;
		}
		if($this->totalPage==0)
		{
			$start=0;
			$end=0;
			$curPage=0;
		}
    // echo  "<pages><page><curPage>$curPage</curPage><startPage>$start</startPage><endPage>$end</endPage><totalPage>$this->totalPage</totalPage></page></pages>";
    return  "<page><curPage>$curPage</curPage><startPage>$start</startPage><endPage>$end</endPage><totalPage>$this->totalPage</totalPage></page>";
}

function getTotalCount($conn,$_table)
{
  $sql = "SELECT * FROM ".$_table;
  try{
  $result=@mysql_query($sql,$conn);
  $tc =@mysql_num_rows($result);
  $this->totalCount=$tc;
  }catch(ErrorException $e)
  {
  	
  //	print_r($e);
  }
 // print("in getTotalCount:"+$tc);
  if($tc%$this->pageSize==0)
  {
  $this->totalPage=$tc/$this->pageSize;
  }else
  {
  	$this->totalPage=intval($tc/$this->pageSize)+1;
  }
 // echo $this->totalPage;
}
}
?>