<?php

// fieldshow : ฟิลด์แรกจะใช้เป็นชื่อ ในการตั้งชื่อของไฟล์ที่อัพโหลด

include("../config/config.php"); 

header ("Content-type: text/html; charset=utf-8");
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache"); // HTTP/1.0
 
$type = pg_escape_string($_GET["type"]);
$condition = pg_escape_string($_GET["condition"]);
$q = pg_escape_string($_GET["q"]);

$qry_dt=pg_query("select \"id\", \"schema\", \"table\", \"fieldsearch\", \"fieldshow\" from \"DocumentType\" WHERE id='$type' ");
if($res_dt=pg_fetch_array($qry_dt)){
    $id = $res_dt["id"];
    $schema = $res_dt["schema"];
    $table = $res_dt["table"];
    $fieldsearch = $res_dt["fieldsearch"];
    $fieldshow = $res_dt["fieldshow"];
        
    $arr_show = explode(",",$fieldshow);
    $arr_nub = count($arr_show);
    foreach($arr_show as $arr_key){
        $i+=1;
        if($arr_nub != $i)
            $show_arr_key .= '"'.$arr_key.'"'.',';
        else
            $show_arr_key .= '"'.$arr_key.'"';
    }
        
    $arr_search = explode(",",$fieldsearch);
    $arr_nub2 = count($arr_search);
    foreach($arr_search as $arr_key){
        $ii+=1;
        if($arr_nub2 != $ii)
            $search_arr_key .= '"'.$arr_key.'"'." LIKE '%$q%' OR ";
        else
            $search_arr_key .= '"'.$arr_key.'"'." LIKE '%$q%' ";
    }

    if(!empty($schema)){
        $my_schema = '"'.$schema.'"';
        $my_table = '"'.$table.'"';
        $s_schema_table = $my_schema.".".$my_table;
    }else{
        $my_table = '"'.$table.'"';
        $s_schema_table = $my_table;
    }
        
	$qry_cn=pg_query("select $show_arr_key from $s_schema_table where $search_arr_key limit 300");
    while($res_cn=pg_fetch_array($qry_cn)){
        $show = '';
        $first_show = '';
        $iii = 0;
        $arr_show = explode(",",$fieldshow);
        $arr_nub5 = count($arr_show);
        foreach($arr_show as $arr_key){
            $iii+=1;
            if($iii==1){
                $first_show = $res_cn["$arr_key"];
            }
                    
            if($iii != $arr_nub5){
                $show .= $res_cn["$arr_key"].' - ';
            }else{
                $show .= $res_cn["$arr_key"];
            }
                    
            if($arr_nub5==$iii){
				$objOpen = opendir("$type");
				$count=0;
				while (($file = readdir($objOpen)) !== false){
					$filecut=substr($file,0,15);
					$filecom=$type."_$first_show"; 
					if($filecut==$filecom){
						$count++;
					}	
				}
					
				if($condition==1 and $count==0){ 
					echo "<li onselect=\"this.setText('$first_show').setValue('$first_show'); \">$show</li>";
				}else{
					if($condition==2){
						echo "<li onselect=\"this.setText('$first_show').setValue('$first_show'); \">$show</li>";
					}
				}
            }
        }
    }
}
?>