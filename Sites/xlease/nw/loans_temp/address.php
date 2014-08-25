<?php
include("../../config/config.php");

$term = trim($_POST['id']);
list($CusID,$nname) = explode('#',$term);

$sql = pg_query("SELECT concat(COALESCE(btrim(\"A_NO\"), ''), '', COALESCE(
                CASE
                    WHEN \"A_SUBNO\" IS NULL OR \"A_SUBNO\" = '-' OR \"A_SUBNO\" = '--' OR \"A_SUBNO\" = ' '  THEN ''
                    ELSE concat(' หมู่ ', btrim(\"A_SUBNO\"))
                END, ''), '', COALESCE(
				CASE
                    WHEN \"A_BUILDING\" IS NULL OR \"A_BUILDING\" = '-' OR \"A_BUILDING\" = '--' OR \"A_BUILDING\" = ' ' THEN ''
                    ELSE concat(' อาคาร', btrim(\"A_BUILDING\"))
                END, ''), '', COALESCE(
				CASE
                    WHEN \"A_ROOM\" IS NULL OR \"A_ROOM\" = '-' OR \"A_ROOM\" = '--' OR \"A_ROOM\" = ' ' THEN ''
                    ELSE concat(' ห้อง', btrim(\"A_ROOM\"))
                END, ''), '', COALESCE(
				CASE
                    WHEN \"A_FLOOR\" IS NULL OR \"A_FLOOR\" = '-' OR \"A_FLOOR\" = '--' OR \"A_FLOOR\" = ' ' THEN ''
                    ELSE concat(' ชั้น', btrim(\"A_FLOOR\"))
                END, ''), '', COALESCE(
				CASE
                    WHEN \"A_VILLAGE\" IS NULL OR \"A_VILLAGE\" = '-' OR \"A_VILLAGE\" = '--' OR \"A_VILLAGE\" = ' ' THEN ''
                    ELSE concat(' หมู่บ้าน', btrim(\"A_VILLAGE\"))
                END, ''), '', COALESCE(
                CASE
                    WHEN \"A_SOI\" IS NULL OR \"A_SOI\" = '-' OR \"A_SOI\" = '--' OR \"A_SOI\" = ' ' THEN ''
                    ELSE concat(' ซอย', btrim(\"A_SOI\"))
                END, ''), '', COALESCE(
                CASE
                    WHEN \"A_RD\" IS NULL OR \"A_RD\" = '-' OR \"A_RD\" = '--' OR \"A_RD\" = ' ' THEN ''
                    ELSE concat(' ถนน', btrim(\"A_RD\"))
                END, ''), '', COALESCE(
                CASE
                    WHEN \"A_TUM\" IS NULL OR \"A_TUM\" = '-' OR \"A_TUM\" = '--' OR \"A_TUM\" = ' ' THEN ''
                    ELSE 
                    CASE
                        WHEN \"A_PRO\" = 'กรุงเทพ' OR \"A_PRO\" = 'กรุงเทพฯ' OR \"A_PRO\" = 'กรุงเทพมหานคร' OR \"A_PRO\" = 'กทม' OR \"A_PRO\" = 'กทม.' THEN concat(' แขวง', btrim(\"A_TUM\"))
                        ELSE concat(' ตำบล', btrim(\"A_TUM\"))
                    END
                END, ''), '', COALESCE(
                CASE
                    WHEN \"A_AUM\" IS NULL OR \"A_AUM\" = '-' OR \"A_AUM\" = '--' OR \"A_AUM\" = ' ' THEN ''
                    ELSE 
                    CASE
                        WHEN \"A_PRO\" = 'กรุงเทพ' OR \"A_PRO\" = 'กรุงเทพฯ' OR \"A_PRO\" = 'กรุงเทพมหานคร' OR \"A_PRO\" = 'กทม' OR \"A_PRO\" = 'กทม.' THEN concat(' เขต', btrim(\"A_AUM\"), ' ')
                        ELSE concat(' อำเภอ', btrim(\"A_AUM\"), ' ')
                    END
                END, ''), '', COALESCE(
                CASE
                    WHEN \"A_PRO\" IS NULL OR \"A_PRO\" = ' ' THEN ''
                    ELSE 
                    CASE
                        WHEN \"A_PRO\" = 'กรุงเทพ' OR \"A_PRO\" = 'กรุงเทพฯ' OR \"A_PRO\" = 'กรุงเทพมหานคร' OR \"A_PRO\" = 'กทม' OR \"A_PRO\" = 'กทม.' THEN btrim(\"A_PRO\")
                        ELSE concat('จังหวัด', btrim(\"A_PRO\"))
                    END
                END, ''), ' ', COALESCE(
                CASE
                    WHEN \"A_POST\" IS NULL OR \"A_POST\" = '-' OR \"A_POST\" = '--' OR \"A_POST\" = '0' OR \"A_POST\" = ' ' THEN ''
                    ELSE btrim(\"A_POST\")
                END, ''), '', '') AS address FROM \"Fa1\" where \"CusID\" = '$CusID'");								 
$row = pg_num_rows($sql);

if($row==0){ //กรณีเป็นลูกค้านิติบุคคล
	$sql=pg_query("select concat(COALESCE(btrim(\"HomeNumber\"), ''),COALESCE(
                CASE
                    WHEN room IS NULL OR room = '-' OR room = '--' THEN ''
                    ELSE concat(' ห้อง ', btrim(room))
                END, ''), '', COALESCE(
                CASE
                    WHEN \"LiveFloor\" IS NULL OR \"LiveFloor\" = '-' OR \"LiveFloor\" = '--' THEN ''
                    ELSE concat(' ชั้น ', btrim(\"LiveFloor\"))
                END, ''), '', COALESCE(
                CASE
                    WHEN \"Moo\" IS NULL OR \"Moo\" = '-' OR \"Moo\" = '--' THEN ''
                    ELSE concat(' หมู่ ', btrim(\"Moo\"))
                END, ''), '', COALESCE(
                CASE
                    WHEN \"Building\" IS NULL OR \"Building\" = '-' OR \"Building\" = '--' THEN ''
                    ELSE concat(' อาคาร/สถานที่ ', btrim(\"Building\"))
                END, ''), '', COALESCE(
                CASE
                    WHEN \"Village\" IS NULL OR \"Village\" = '-' OR \"Village\" = '--' THEN ''
                    ELSE concat(' หมู่บ้าน ', btrim(\"Village\"))
                END, ''), '', COALESCE(
                CASE
                    WHEN \"Lane\" IS NULL OR \"Lane\" = '-' OR \"Lane\" = '--' THEN ''
                    ELSE concat(' ซอย', btrim(\"Lane\"))
                END, ''), '', COALESCE(
                CASE
                    WHEN \"Road\" IS NULL OR \"Road\" = '-' OR \"Road\" = '--' THEN ''
                    ELSE concat(' ถนน', btrim(\"Road\"))
                END, ''), '', COALESCE(
                CASE
                    WHEN \"District\" IS NULL OR \"District\" = '-' OR \"District\" = '--' THEN ''
                    ELSE 
                    CASE
                        WHEN \"ProvinceID\" = '02' THEN concat(' แขวง', btrim(\"District\"))
                        ELSE concat(' ตำบล', btrim(\"District\"))
                    END
                END, ''), '', COALESCE(
                CASE
                    WHEN \"State\" IS NULL OR \"State\" = '-' OR \"State\" = '--' THEN ''
                    ELSE 
                    CASE
                        WHEN \"ProvinceID\" = '02' THEN concat(' เขต', btrim(\"State\"), ' ')
                        ELSE concat(' อำเภอ', btrim(\"State\"), ' ')
                    END
                END, ''), '', COALESCE(
                CASE
                    WHEN \"Postal_code\" IS NULL THEN ''
                    ELSE 
                    CASE
                        WHEN \"ProvinceID\" = '02' THEN btrim(g.\"proName\")
                        ELSE concat('จังหวัด', btrim(g.\"proName\"))
                    END
                END, ''), ' ', COALESCE(btrim(\"Postal_code\"), ''), '') AS address from th_corp_adds 
				LEFT JOIN nw_province g ON th_corp_adds.\"ProvinceID\" = g.\"proID\"
				where \"corpID\"::text='$CusID'");
	$row2 = pg_num_rows($sql);
}
$re = pg_fetch_array($sql);

if(($row == 0 || empty($row)) and ($row2 == 0 || empty($row2))){

echo "..........";

}else{
echo $re['address'];
}
?>