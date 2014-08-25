<?php
	$no = 1;
?>
<div style="padding-top:50px;"></div>
<table width="90%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
    <tr bgcolor="#FFFFFF">
        <td colspan="14" align="left" style="font-weight:bold;">
        	<font style="color:#000000; font-weight:bold;">
			ประวัติการอนุมัติ 30 รายการล่าสุด (<a style="color:#0099FF;cursor:pointer;" onclick="javascript:popU('notice_approve_history.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1400,height=650')"><u>ทั้งหมด</u></a>) </font>
        	</font>
        </td>
    </tr>
    <tr style="font-weight:bold;" valign="middle" bgcolor="#B5B5B5">
        <td align="center">ลำดับ</td>
        <td align="center">เลข NT</td>
        <td align="center">เลขที่สัญญา</td>
        <td align="center">ชื่อ</td>
        <td align="center">ทะเบียน</td>
        <td align="center">สีรถ</td>
        <td align="center">เหตุผล</td>
        <td align="center">ผู้ออกหนังสือ</td>
        <td align="center">ผู้ที่ยกเลิกหนังสือ</td>
        <td align="center">วันยกเลิกหนังสือ</td>
        <td align="center">ผู้อนุมัติ</td>
        <td align="center">วันเวลาที่อนุมัติ</td>
        <td align="center">สถานะ</td>
        <!--<td align="center">เหตุผล</td>-->
        
        
    </tr>

<?php
        $qry_fr=pg_query("		
--อนุมัติ

(
SELECT e.\"runrow\",a.\"IDNO\",a.\"NTID\",a.\"cancel\",c.\"fullname\" as \"markfullname\",d.\"fullname\" as \"canfullname\",a.\"cancel_date\",f.\"fullname\" as \"appfullname\",e.\"app_date\",e.\"noteapp\"
FROM \"NTHead\" a 
LEFT JOIN \"nw_statusNT\" b ON a.\"NTID\" = b.\"NTID\"
LEFT JOIN \"NTHead_log_notappvcancel\" e ON a.\"NTID\" = e.\"NTID\"
LEFT JOIN \"Vfuser\" c ON a.\"makerid\" = c.\"id_user\"
LEFT JOIN \"Vfuser\" d ON a.\"cancelid\" = d.\"id_user\"
LEFT JOIN \"Vfuser\" f ON e.\"app_user\" = f.\"id_user\"	
WHERE a.\"CusState\"='0' AND a.\"cancel\"='TRUE' AND a.\"cancelid\" IS NOT NULL 
)


UNION ALL

--ไม่อนุมัติ

(
SELECT e.\"runrow\",a.\"IDNO\",a.\"NTID\",a.\"cancel\",c.\"fullname\" as \"markfullname\",d.\"fullname\" as \"canfullname\",e.\"cancel_date\",f.\"fullname\" as \"appfullname\",e.\"app_date\",e.\"noteapp\"
FROM \"NTHead\" a 
LEFT JOIN \"nw_statusNT\" b ON a.\"NTID\" = b.\"NTID\"
LEFT JOIN \"NTHead_log_notappvcancel\" e ON a.\"NTID\" = e.\"NTID\"
LEFT JOIN \"Vfuser\" c ON a.\"makerid\" = c.\"id_user\"
LEFT JOIN \"Vfuser\" d ON e.\"cancelid_old\" = d.\"id_user\"
LEFT JOIN \"Vfuser\" f ON e.\"app_user\" = f.\"id_user\"
WHERE a.\"CusState\"='0' AND a.\"cancel\"='FALSE' AND  a.\"NTID\" IN ( 
SELECT y.\"NTID\" FROM \"NTHead_log_notappvcancel\" y 
LEFT JOIN \"NTHead\" z ON y.\"NTID\" = z.\"NTID\"
WHERE z.\"CusState\"='0' AND y.\"noteapp\" is not null
)
)	
ORDER BY \"app_date\" DESC NULLS LAST,\"cancel_date\" DESC LIMIT 30");

        $nub=pg_num_rows($qry_fr);
        while($res_fr=pg_fetch_array($qry_fr)){
            $IDNO = $res_fr["IDNO"];
            $NTID = $res_fr["NTID"];
            $markfullname =  $res_fr["markfullname"];
            $canfullname =  $res_fr["canfullname"];
            $cancel_date =  $res_fr["cancel_date"];
            $appfullname =  $res_fr["appfullname"];
            $app_date =  $res_fr["app_date"];
            $noteapp =  $res_fr["noteapp"];
            $cancelstatus = $res_fr["cancel"];
            $runrow = $res_fr["runrow"];
            IF($noteapp != ""){
                $statustxt = 'ไม่อนุมัติ';
            }ELSE{
                $statustxt = 'อนุมัติ';								
            }
            
            
            $qry_vc=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$IDNO' ");
            if($res_vc=pg_fetch_array($qry_vc)){
                $full_name = $res_vc["full_name"];
                $C_COLOR = $res_vc["C_COLOR"];
                $asset_type = $res_vc["asset_type"];
                $C_REGIS = $res_vc["C_REGIS"];
                $car_regis = $res_vc["car_regis"];
                
                if($asset_type == 1) $show_regis = $C_REGIS; else $show_regis = $car_regis;
            }

            $i+=1;
            if($i%2==0){
                echo "<tr bgcolor=#CFCFCF onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#CFCFCF';\" align=center>";
            }else{
                echo "<tr bgcolor=#E8E8E8 onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#E8E8E8';\" align=center>";
            }
        ?>
        <td align="center"><?php echo $no; ?></td>
        <td align="center">
            <font color="blue">
                <a onclick="javascript:popU('../nw/showNT/notice_reprint_pdf.php?idno=<?php echo $IDNO; ?>&ntid=<?php echo $NTID?>','<?php echo "$IDNO_outstanding"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')" style="cursor: pointer;" title="รายละเอียด NT">
                    <u>
                        <?php echo $NTID; ?>
                    </u>
                </a>
            </font>	
        </td>
        <td align="center">
            <font color="blue">
                <a onclick="javascript:popU('../post/frm_viewcuspayment.php?idno_names=<?php echo $IDNO; ?>&type=outstanding','<?php echo "$IDNO_outstanding"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')" style="cursor: pointer;" title="ดูตารางการชำระ">
                    <u>
                        <?php echo $IDNO; ?>
                    </u>
                </a>
            </font>	
        </td>
        <td align="left"><?php echo $full_name; ?></td>
        <td align="left"><?php echo $show_regis; ?></td>
        <td align="left"><?php echo $C_COLOR; ?></td>
        <td align="center">
            <font color="blue">
                <a onclick="javascript:popU('notice_approve_remark.php?ntid=<?php echo "$NTID"; ?>','<?php echo "$NTID_approve_remark"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=500,height=250')" style="cursor: pointer;" title="Re Print">
                    <u>
                        รายละเอียด
                    </u>
                </a>
            </font>	
        </td>
        <td align="left"><?php echo $markfullname; ?></td>
        <td align="left"><?php echo $canfullname; ?></td>
        <td align="center"><?php echo $cancel_date; ?></td>
        <td align="left"><?php echo $appfullname; ?></td>
        <td align="center"><?php echo $app_date; ?></td>
        <td align="center"><?php echo $statustxt; ?></td>
       <!-- <?php if($noteapp == ""){ ?>
        <td align="center"></td>								
        <?php }else{ ?>		
        <td align="center"><a onclick="javascript:popU('frm_notepop.php?runrow=<?php echo $runrow; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=500,height=250')" style="cursor:pointer;" title="note"><u>แสดงเหตุผล</u></a></td>
        <?php } ?> -->
        
    </tr>
<?php
    unset($statustxt);
    //บวกจำนวนอันดับ
        $no++;
    
    }//ปิด While

    if($nub == 0){
        echo "<tr><td colspan=14 align=center bgcolor=#B5B5B5>- ไม่พบข้อมูล -</td></tr>";
    }
?>
</table>