<?php
function showUserSpend($r){	
	if ($r <= 0){
		$s = 'ไม่ค่าถูกส่งมา';
	}else{
		$s = '';
		foreach(array(86400=>'วัน',3600=>'ชั่วโมง',60=>'นาที',1=>'วินาที') as $p=>$suffix){
			if ($r >= $p){
				$r -= $d = $r-$r%$p;
				$s .= $d/$p." $suffix ";
			}
		}
	}
	return $s;
}

?>