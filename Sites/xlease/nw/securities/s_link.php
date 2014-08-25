<?php
include("../../config/config.php");
$term = $_GET['term'];

$qry_name=pg_query("select distinct(a.\"numid\") as numid2 from \"nw_linksecur\" a
					left join nw_linknumsecur b on a.\"numid\"=b.\"numid\"
					left join \"nw_linkIDNO\" c on a.\"numid\"=c.\"numid\"
					left join \"nw_securities\" d on b.\"securID\"=d.\"securID\"
					where CAST(a.\"numid\" AS character varying) like '%$term%' or c.\"IDNO\" like '%$term%' or d.\"numDeed\" like '%$term%' order by a.\"numid\"");

$numrows = pg_num_rows($qry_name);

//ดึงข้อมูลขึ้นมาแสดงจาก numid ที่ได้
while($res_search=pg_fetch_array($qry_name)){
	$numid=$res_search["numid2"];
					
	//แสดงข้อมูลโฉนดที่ดิน
	$qry_numdeed=pg_query("select b.\"numDeed\" from nw_linknumsecur a
		left join \"nw_securities\" b on a.\"securID\"=b.\"securID\"
		left join \"nw_linkIDNO\" c on a.\"numid\"=c.\"numid\"
		where a.\"numid\"='$numid' ");
					
	//echo $qry_numdeed;
	$i=1;
	$numdeed="";
	$numdeedold="";
	while($res_numdeed=pg_fetch_array($qry_numdeed)){
		$numdeed2=$res_numdeed["numDeed"];
		if($i==1){
			$numdeed=$numdeed2;
		}else{
			if($numdeedold==$numdeed2){
				$numdeed=$numdeed;
			}else{
				$numdeed=$numdeed.", ".$numdeed2;
			}
		}
		$i++;
		$numdeedold=$numdeed2;
	}
					
	//แสดงข้อมูลเลขที่สัญญา
	$qry_IDNO=pg_query("select \"IDNO\" from \"nw_linkIDNO\" where \"numid\"='$numid'");
	$i=1;
	$IDNO="";
	$IDNOOLD="";
	while($res_IDNO=pg_fetch_array($qry_IDNO)){
		$IDNO2=$res_IDNO["IDNO"];
		if($i==1){
			$IDNO=$IDNO2;
		}else{
			if($IDNOOLD==$IDNO2){
				$IDNO=$IDNO;
			}else{
				$IDNO=$IDNO.", ".$IDNO2;
			}
		}
		$i++;
		$IDNOOLD=$IDNO2;
	}
	
	$dt['value'] = $numid."#เลขที่โฉนด ".$numdeed."#เลขที่สัญญา ".$IDNO;
    $dt['label'] = "เลขที่โฉนด {$numdeed}, เลขที่สัญญา {$IDNO}";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
