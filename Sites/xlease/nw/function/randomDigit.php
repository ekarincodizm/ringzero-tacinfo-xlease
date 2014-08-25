<?php
function randomDigit($data){
	for($i=0;$i<$data;$i++){
		$rand =  $rand .  rand(0,9); 
	}
	return $rand;
}
?>