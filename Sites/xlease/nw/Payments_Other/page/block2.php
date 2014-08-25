<div class="maindiv11">
    <div class="maindivlabel11"><div class="divlabeltext"><span class="nonehilight">ค่าใช้จ่ายอื่น ๆ </span><span class="nonehilight">:::</span><span class="nonehilight">ยอดค้างชำระ </span><span id="spantotalamount"></span><span class="nonehilight"> บาท</span></div></div>
    <center>
    <div class="divtb1_both">
        <span id="tb1_chkbox1"><input type="checkbox" name="interestRatePost" id="interestRatePost" onChange="receivewhtchk(); test(); truemoneyother();"><label>ภาษีหัก ณ ที่จ่าย</label></span>
        <span id="tb1_chkbox_WHT">
            <font id="fontwht"> เลขที่อ้างอิง : </font>
            <input type="text" name="whtDetail" id="whtDetail">
        </span>
    </div><br>
    <table align="center" border="0" cellspacing="1" cellpadding="1" bgcolor="#FFFFFF" id="tb1">
        <tr bgcolor="#4399c3" style="color:#FFFFFF; font-family:Arial, Helvetica, sans-serif; font-size:14px;" height="25">
            <th width="150">รหัสประเภทค่าใช้จ่าย</th>
            <th width="200">รายการ</th>
            <th width="150">ค่าอ้างอิงของค่าใช้จ่าย</th>
            <th width="130">วันที่ตั้งหนี้</th>
            <th width="120">จำนวนหนี้</th>
            <th width="170">ผู้ตั้งหนี้</th>
            <th width="180">วันเวลาตั้งหนี้</th>
            <th width="80" id="moneyth">ภาษีหัก <br>ณ ที่จ่าย</th>
            <th width="80">ทำรายการ</th>
        </tr>
        <?php
        
        $SumtypePayAmt = 0;
        if($numrows == 0)
        {
            echo "<tr><td colspan=\"8\" style=\"text-align:center;\">ไม่พบรายการหนี้อื่นๆค้างชำระ</td></tr>";
            echo "<input type=\"hidden\" id=\"chk0\" name=\"chk[]\">";
        }
        else
        {
            $num = 0;
            while($res_name=pg_fetch_array($qry_name))
            {
                $typePayID=trim($res_name["typePayID"]); // รหัสประเภทค่าใช้จ่าย
                $typePayRefValue=trim($res_name["typePayRefValue"]);
                $typePayRefDate=trim($res_name["typePayRefDate"]); // วันที่ตั้งหนี้
                $typePayLeft=trim($res_name["typePayLeft"]);
                $doerID=trim($res_name["doerID"]); 
                $doerStamp=trim($res_name["doerStamp"]);
                $debtID=trim($res_name["debtID"]); // รหัสหนี้
                $contractID=trim($res_name["contractID"]);
                
                //-------------------------------
                if($typePayID == "1003")
                {
                    $search = strpos($typePayRefValue,"-");
                    if($search)
                    {
                        $subtypePayRefValue = explode("-", $typePayRefValue);
                        $typePayRefValue = $subtypePayRefValue[0];
                    }
                }
                //--------------------------------
                
                $doerStamp = substr($doerStamp,0,19); // ทำให้อยู่ในรูปแบบวันเวลาที่สวยงาม
                
                if($doerID == "000")
                {
                    $doerName = "อัตโนมัติโดยระบบ";
                }
                else
                {
                    $doerusername=pg_query("select * from public.\"Vfuser\" where \"id_user\"='$doerID'");
                    while($res_username=pg_fetch_array($doerusername))
                    {
                        $doerName=$res_username["fullname"];
                    }
                }
                
                $qry_type=pg_query("select * from account.\"thcap_typePay\" where \"tpID\"='$typePayID' ");
                while($res_type=pg_fetch_array($qry_type))
                {
                    $tpDesc=trim($res_type["tpDesc"]); // รายละเอียดประเภทค่าใช้จ่าย
                }
                
                $due = ""; // กำหนดเป็นค่าว่างเพื่อป้องกันการเก็บค่าเก่ามาใช้
                
                if($typePayID == "1003")
                {
                    $qry_due=pg_query("select * from account.\"thcap_mg_payTerm\" where \"contractID\"::text='$ConID' and \"ptNum\"::text='$typePayRefValue' ");
                    while($res_due=pg_fetch_array($qry_due))
                    {
                        $ptDate=trim($res_due["ptDate"]); // วันดิว
                        $due = "($ptDate)";
                    }
                }
                else
                {
                    $due = "";
                }
				
				// ตรวจสอบว่าหนี้นั้นๆถูกขอยกเลิกอยู่หรือไม่
				$qry_chk_exceptDebt = pg_query("select * from \"thcap_temp_except_debt\" where \"debtID\" = '$debtID' and \"Approve\" is null ");
				$row_chk_exceptDebt = pg_num_rows($qry_chk_exceptDebt);
				if($row_chk_exceptDebt == 0){$haveExceptDebt = "no";}else{$haveExceptDebt = "yes";}
				// จบการตรวจสอบว่าหนี้นั้นๆถูกขอยกเลิกอยู่หรือไม่
                
                // หาภาษีหัก ณ ที่จ่าย
                $whtAmtfunc = pg_query("SELECT \"thcap_checkdebtwht\"('$debtID','$currentDate')");					
                $whtAmt1 = pg_fetch_array($whtAmtfunc);
                $whtAmt = $whtAmt1['thcap_checkdebtwht'];
                
				if($statusLock == 1)
				{
					if(date($dateContact) < date($typePayRefDate))
					{ // ถ้าวันที่โอนเงินมากกว่าวันที่ตั้งหนี้
						echo "<tr bgcolor=\"#DBF2FD\"style=\"color:#444; background:#FFBBBB; font-family:Arial, Helvetica, sans-serif; font-size:14px;\">";
					}
					else
					{
						echo "<tr bgcolor=\"#DBF2FD\"style=\"color:#444; font-family:Arial, Helvetica, sans-serif; font-size:14px;\">";
					}
				}
				else
				{
					echo "<tr bgcolor=\"#DBF2FD\"style=\"color:#444; font-family:Arial, Helvetica, sans-serif; font-size:14px;\">";
				}
				
				echo "<td align=center>$typePayID</td>";
                echo "<td align=center>$tpDesc</td>";
                echo "<td align=center>$typePayRefValue $due</td>";
                echo "<td align=center>$typePayRefDate</td>";
                echo "<td align=right>".number_format($typePayLeft,2)."</td>";
                echo "<td align=center>$doerName</td>";
                echo "<td align=center>$doerStamp</td>";
                echo "<td align=center id=\"moneyth1$num\"><input id=\"moneytxt$num\" name=\"moneytxt$num\" oncontextmenu=\"return false\" onkeypress=\"check_num(event);\" onKeyUp=\"javascript:calculate(); test(); truemoneyother();\" type=\"textbox\" value=\"".$whtAmt."\" size=\"12\" style=\"text-align:right;\"></td>";	
                echo "<input type=\"hidden\" id=\"CHKmoneytxt$num\" value=\"".$whtAmt."\" >";
				if($haveExceptDebt == "no")
				{ // ถ้าไม่มีการขอยกเว้นหนี้อยู่
					echo "<td align=center><input id=\"chk$num\" name=\"chk[]\" type=\"checkbox\" value=\"$typePayLeft $debtID $typePayID $num\" onclick=\"chkTypePayRefDate($num); calculate(); test(); truemoneyother();\"></td>";				
                }
				else
				{ // ถ้ามีการขอยกเว้นหนี้
					echo "<td align=center><input id=\"chk$num\" name=\"chk[]\" type=\"checkbox\" value=\"$typePayLeft $debtID $typePayID $num\" onclick=\"chkTypePayRefDate($num); calculate(); test(); truemoneyother();\" disabled hidden>รออนุมัติการ<br>ยกเว้นหนี้อยู่</td>";
				}
				echo "</tr>";
				
				echo "<input type=\"hidden\" name=\"typePayRefDate$num\" id=\"typePayRefDate$num\" value=\"$typePayRefDate\" >";
                
                $SumtypePayAmt += $typePayLeft; // ยอดหนี้อื่นๆรวม
                $num++;
            }
            echo "<input type=\"hidden\" name=\"hidenum\" id=\"hidenum\" value=\"$num\">";
        }
        
        $SumAll = $SumtypePayAmt + $nextDueAmt; // รวมยอดค้างชำระ
        
        echo "<input type=\"hidden\" id=\"ConID3\" name=\"ConID3\" value=\"$ConID\">";
        //echo "<input type=\"hidden\" id=\"hidetotal\" name=\"hidetotal\" value=\"$SumAll\">";
        echo "<input type=\"hidden\" id=\"hidetotal\" name=\"hidetotal\" value=\"".number_format($SumtypePayAmt,2)."\">";
        ?>
    </table>
    <div class="divtb1_both">
        <!--<span id="tb1_chkbox1"><input type="checkbox" name="interestRatePost" id="interestRatePost"><label>ภาษีหัก ณ ที่จ่าย</label></span>-->
        <span id="tb1_chkbox2"><input type="checkbox" name="receiveVice" id="receiveVice" value="1" onChange="receivevicechk()"><label>เป็นใบเสร็จออกแทน</label></span>
        <span id="tb1_chkbox3">
            <select name="selectVice" id="selectVice">
                <option value="ใบเสร็จชั่วคราวเลขที่">ใบเสร็จชั่วคราวเลขที่</option>
                <option value="ใบเสร็จที่ยกเลิกเลขที่">ใบเสร็จที่ยกเลิกเลขที่</option>
            </select>
            &nbsp;<input type="text" name="viceDetail" id="viceDetail">
            &nbsp;| รวมค่าอื่นๆที่จะชำระ :<input type="textbox" id="sum1" name="sum1" style="text-align: right;background-Color:#CCCCCC;" value="0" readOnly="true" > | รวมภาษีหัก ณ ที่จ่ายค่าอื่นๆ :<input type="textbox" id="sum2" name="sum2" value="0" style="text-align: right;background-Color:#CCCCCC;" readonly="true">
        </span>
    </div>
    <div class="divtb1_both">
        <!--<span id="tb1_chkbox1"><input type="checkbox" name="interestRatePost" id="interestRatePost"><label>ภาษีหัก ณ ที่จ่าย</label></span>-->
        <span id="tb1_chkbox2"><input type="checkbox" name="chkreasonother" id="chkreasonother" value="1" onChange="typereason(id)"><label>หมายเหตุ :</label></span>
        <span id="tb1_chkbox3"><input type="text" name="reasontextother" id="reasontextother" size="100"></span>
    </div>
    
    
    </center>
</div>