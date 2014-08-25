<?php
	// หาอัตราดอกเบี้ยปัจจุบันของสัญญา
	if($dateContact != ""){$dateForIntRate = $dateContact;}else{$dateForIntRate = $nowDate;}
	$qry_nowConIntCurRate = pg_query("SELECT \"thcap_getLastIntRate\"('$ConID','$dateForIntRate')");
	$nowConIntCurRate = pg_fetch_result($qry_nowConIntCurRate,0);
	
	//สัญญาประเภท
	$creditType= pg_query("select \"thcap_get_creditType\"('$ConID')");
	list($creditType1) = pg_fetch_array($creditType);	//ประเภทสัญญา
    $conMonthlyAdviserFee="";
	if($creditType1=="JOINT_VENTURE")
	{
		$qryconMonthlyAdviserFee=pg_query("select \"conMonthlyAdviserFee\"  from \"thcap_contract\" where \"contractID\"='$ConID'");	
		list($conMonthlyAdviserFee) = pg_fetch_array($qryconMonthlyAdviserFee);	
	}
?>
<div id="divfloatleft">
    <div class="maindivlabel2">
        <div class="divlabeltext">
            <span class="nonehilight"><input id="appent" name="appent" type="checkbox" onChange="chkAdd(); truemoney();" <?php if($haveCancelPayment > 0){echo "disabled";} ?>><label>ชำระเงินกู้จำนองชั่วคราว(THCAP)ด้วย</label></span>
            <span class="nonehilight">:::</span>
            <span class="nonehilight">ยอดค้างชำระปัจจุบัน </span><span class="spanhilight"><?php echo number_format($backAmt,2); ?></span><span class="nonehilight"> บาท</span>
            <span class="nonehilight">:::</span>
            <span class="nonehilight">วันที่เริ่มค้างชำระ </span><span class="spanhilight"><?php echo $backDueDate; ?></span>
            <span class="nonehilight">:::</span>
            <span class="nonehilight">ยอดครบกำหนดวันที่ </span><span class="spanhilight"><?php echo $nextDueDate ?></span>
            <span class="nonehilight">:::</span>
            <span class="nonehilight">จำนวน </span><span class="spanhilight"><?php echo number_format($nextDueAmt,2); ?></span><span class="nonehilight"> บาท</span>
        </div>
    </div>
    <!--<div class="divchkboxcontrainer">
        <span><input id="appent" name="appent" type="checkbox" onchange="chkAdd()"><label>ชำระเงินกู้จำนองชั่วคราวด้วย(THCAP)</label></span>
    </div>-->
</div>
<div id="divtb2" class="maindiv12">
    <center>
    <div class="divtb1_both22">
        <span id="tb1_chkbox1"><input type="checkbox" name="interestRatePost_Payment" id="interestRatePost_Payment" onChange="receivewhtchk_Payment(); truemoney();"><label>ภาษีหัก ณ ที่จ่าย</label></span>
        <span><font id="fontwht_Payment"> เลขที่อ้างอิง : </font><input type="text" name="whtDetail_Payment" id="whtDetail_Payment"></span>
        <span><font id="txtwhtmain"> จำนวนเงินภาษีหัก ณ ที่จ่าย : </font><input type="text" id="sum3" name="sum3" onkeypress="check_num(event);" onKeyUp="javascript:chkOverWht(); truemoney();" style="text-align: right;"></span>
        <input type="hidden" id="CHKsum3">
    </div><br>
    <ul id="ul_tb2">
        <li>
            <span id="spantab"><span class="nonehilight">ยอดจ่ายขั้นต่ำต่อเดือน </span><span class="spanhilight"><?php echo number_format($conMinPay,2); ?></span><span class="nonehilight"> บาท</span></span>
			<!--เพิ่ม ค่าที่ปรึกษากิจการร่วมค้าต่องวด-->			
			<?php	if($creditType1=="JOINT_VENTURE"){ ?>
			<span id="spantab"><span class="nonehilight">ค่าที่ปรึกษากิจการร่วมค้าต่องวด : </span> 
			<span class="spanhilight">
			<?php IF($conMonthlyAdviserFee!="") 
			{   
			    echo number_format($conMonthlyAdviserFee,2); ?>
				</span><span class="nonehilight"> บาท</span></span>	
			<?php } 
			}?>	
		</li>
        <li>
            <ul>
                <li class="long">จำนวนเงินค่างวดที่ต้องการชำระ</li>
                <li class="long">อัตราดอกเบี้ย</li>
                <li></li>
            </ul>
        </li>
        <li>
            <ul>
                <li class="long">
                    <input type="text" id="t2" name="t2" oncontextmenu="return false" onKeyUp="javascript:test();" onFocus="clearNum(id)" class="textbox_tb2" onkeypress="check_num(event);" onBlur="checkNull(id)" value="0">
                </li>
                <li class="long">
                    <input type="text" id="t3" name="t3" class="textbox_tb2" onBlur="checkNull(id)" value="<?php echo $nowConIntCurRate; ?>" readonly style="background:#CCCCCC;">
                </li>
                <li class="long"><span style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:darkgreen;">* อัตราดอกเบี้ยปัจจุบันของสัญญา <?php echo $conIntCurRate; ?>%</span></li>
            </ul>
        </li>
    </ul>
    <div class="divtb1_both22">
        <!--<span id="tb1_chkbox1"><input type="checkbox" name="interestRatePost_Payment" id="interestRatePost_Payment"><label>ภาษีหัก ณ ที่จ่าย</label></span>-->
        <span id="tb1_chkbox5"><input type="checkbox" name="receiveVice_Payment" id="receiveVice_Payment" value="1" onChange="receivevicechk_Payment()"><label>เป็นใบเสร็จออกแทน</label></span>
        <span id="tb1_chkbox_Payment">
            <select name="selectVice_Payment" id="selectVice_Payment">
            <?php 
                $sqlrein = pg_query("SELECT re_inname FROM thcap_receipt_instead_type"); 
                    while($reinre = pg_fetch_array($sqlrein)){ ?>
                        <option value="<?php echo $reinre['re_inname'] ?>"><?php echo  $reinre['re_inname'] ?></option>
                <?php	} ?>
            </select>
            &nbsp;<input type="text" name="viceDetail_Payment" id="viceDetail_Payment">
        </span>
    </div>
        <div class="divtb1_both22">
        <!--<span id="tb1_chkbox1"><input type="checkbox" name="interestRatePost" id="interestRatePost"><label>ภาษีหัก ณ ที่จ่าย</label></span>-->
        <span id="tb1_chkbox5"><input type="checkbox" name="chkreasonappent" id="chkreasonappent" value="1" onChange="typereasonloan(id)" ><label>หมายเหตุ :</label></span>
        <span id="tb1_chkbox3"><input type="text" name="reasontextappent" id="reasontextappent" size="100"></span>
    </div>
    </center>
</div>