<script type="text/javascript">
var gFiles=0;
function addFile(){
//กด เพิ่มรายการ ในการบันทึเอง
    var li = document.createElement('div');
    li.setAttribute('id', 'file-' + gFiles);
    li.innerHTML = '<div align="left">เลือกบัญชี <select name="acid[]" id="acid" onchange="getValueArray(); "><option value="">- เลือก -</option><?php
	$qry_name=pg_query("SELECT * FROM account.\"V_all_accBook\" ORDER BY \"accBookID\" ASC");
	while($res_name=pg_fetch_array($qry_name)){
		$AcSerial = $res_name["accBookserial"]; // รหัสบัญชี
		$AcID = $res_name["accBookID"]; // เลขที่บัญชี
		$AcName = $res_name["accBookName"]; // ชื่อบัญชี
		echo "<option value=\"$AcSerial\">$AcID : $AcName</option>";
}
?></select> สถานะ <select name="actype[]" id="actype" onchange="getValueArray(); chk4700();"><option value="">- เลือก -</option><option value="1">Dr</option><option value="2">Cr</option></select> ยอดเงิน <input type="text" id="text_money" name="text_money[]" size="30" onblur="chk_2fix(this)"><span onclick="removeFile(\'file-' + gFiles + '\'), getValueArray();" style="cursor:pointer;"><i>- ลบรายการนี้ -</i></span></div>';
    document.getElementById('files-root').appendChild(li);
    gFiles++;
}
function removeFile(aId) {
//กด ลบรายการนี้
    var obj = document.getElementById(aId);
    obj.parentNode.removeChild(obj);
}
function getValueArray(){
    var a1=0;
    var a0=0;
    var sum1 = 0;
    var sum0 = 0;
    var money1;
    var str = "<table cellSpacing=\"1\" cellPadding=\"3\" border=\"0\" width=\"100%\" style=\"background-color:#ACACAC; color:#000000;\"><tr bgcolor=\"#FFFFD2\"><td align=\"center\"><b>บัญชี</b></td><td align=\"center\"><b>Dr</b></td><td align=\"center\"><b>Cr</b></td></tr>";
   
    var acid = window.document.getElementsByName("acid[]");
    var actype = window.document.getElementsByName("actype[]");
    var text_money = window.document.getElementsByName("text_money[]");
	
	
    var actype_length = actype.length;

    for(i = 0; i < actype_length; i++){
        if(actype[i].value == ''){}
        else if(actype[i].value == 1){
            var index = acid[i].selectedIndex;			
            if(index != ''){
                select_text = document.getElementById('acid').options[index].text;
                sum1 = sum1 + (text_money[i].value*1);
                a1 = a1+1;
                str += "<tr bgcolor=\"#FFFFFF\"><td>"+select_text+"</td><td align=\"right\">"+text_money[i].value+"</td><td></td></tr>";
            }
        }
    }
    sum1 = sum1.toFixed(2);

    for(i = 0; i < actype_length; i++){
        if(actype[i].value == ''){}
        else if(actype[i].value == 2){            
            var index = acid[i].selectedIndex;
            if(index != ''){
                select_text = document.getElementById('acid').options[index].text;
                
                sum0 = sum0 + (text_money[i].value*1);
                a0 = a0+1;
                str += "<tr bgcolor=\"#FFFFFF\"><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+select_text+"</td><td></td><td align=\"right\">"+text_money[i].value+"</td></tr>";
            }
        }
    }
	
    sum0 = sum0.toFixed(2);    
    if((sum1 == sum0) && a1 > 0 && a0 > 0){
        document.getElementById('chk_drcr').value = 0;
    }else{
         document.getElementById('chk_drcr').value = 1;
    }    
    str += "<tr bgcolor=\"#FFFFFF\"><td align=\"right\"><b>รวม</b></td><td align=\"right\"><b>"+sum1+"</b></td><td align=\"right\"><b>"+sum0+"</b></td></tr>";
    str += "</table>";	
    document.getElementById('myDiv').innerHTML = str;

}
function chk4700(){
    var nubbb = 0;
    
    var arr_acid = window.document.getElementsByName("acid[]");
    var arr_actype = window.document.getElementsByName("actype[]");
    var arr_text_money = window.document.getElementsByName("text_money[]");
    var actype_length = arr_actype.length;
    
    for(i = 0; i < actype_length; i++){
        if(arr_acid[i].value == 4700 && arr_actype[i].value == 1 && arr_text_money[i].value != ""){
            nubbb++;
        }
    }
    
    if(nubbb > 0){      
        $("#hidchk").val('1');
    }else{       
        $("#hidchk").val('0');
    }
}	
function chk_inputdata_accdetail (){
	var theMessage="";
	var x1=0;
    var acid = window.document.getElementsByName("acid[]");
	
    for(i = 0; i < acid.length; i++){
        if(acid[i].value == ''){
			
                x1 = x1+1;
        }
   }
        
    var x2=0;
    var actype = window.document.getElementsByName("actype[]");
    for(i = 0; i < actype.length; i++){
        if(actype[i].value == ''){
                x2 = x2+1;
        }
    }
        
    var x3=0;
	var x4=0;
    var text_money = window.document.getElementsByName("text_money[]");
    for(i = 0; i < text_money.length; i++){
        if(text_money[i].value == ''){
            x3 = x3+1;
        }
    }
        
    if(x1 > 0){        
		theMessage = theMessage + "\n -->  พบรายการบัญชี ไม่ถูกเลิก" ;
    }else if(x2 > 0){
        theMessage = theMessage + "\n -->  พบสถานะ ไม่ถูกเลิก" ;
    }else if(x3 > 0){
        theMessage = theMessage + "\n -->  ไม่พบยอดเงิน" ;        
    }else if($("#chk_drcr").val() == 1){
        theMessage = theMessage + "\n -->  ผลรวม Dr และ Cr ไม่เท่ากัน" ;      
    }
	return theMessage;
}
function chk_2fix(obj){  
	var number = obj.value;
	if(number !=""){	
	var num = parseFloat(number);
	var result = num .toFixed(2);
	if(num !=result){
		alert('กรุณาป้อนทศนิยม 2 ตำแหน่ง');
		obj.value="";
		getValueArray();
		chk4700();
	}
	else{
		obj.value=result;
		getValueArray();
		chk4700();
	}
	}
}
</script>
<table align="left"><tr><td>	
	<div style="float:right"><input type="button" value="เพิ่มรายการ" class="ui-button" id="btn_add" name="btn_add" onclick= "addFile();getValueArray();"></div>	
</td></tr>
<input type="hidden" id="chk_drcr" name="chk_drcr">	
	<tr><td>
		<div id="made01" style="background-color:#F0F0F0; padding: 3px 3px 3px 3px; border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px">
			<div id="files-root">
				<div align="left">เลือกบัญชี
					<select name="acid[]" id="acid" onchange="getValueArray(); chk4700();">
					<option value="">- เลือก -</option>
					<?php
						$qry_name=pg_query("SELECT * FROM account.\"V_all_accBook\" ORDER BY \"accBookID\" ASC");
						while($res_name=pg_fetch_array($qry_name))
						{
							$AcSerial = $res_name["accBookserial"]; // รหัสบัญชี
							$AcID = $res_name["accBookID"]; // เลขที่บัญชี
							$AcName = $res_name["accBookName"]; // ชื่อบัญชี
							echo "<option value=\"$AcSerial\">$AcID : $AcName</option>";
						}
					?>
					</select> สถานะ
					<select name="actype[]" id="actype" onchange="getValueArray(); chk4700();">
						<option value="">- เลือก -</option>
						<option value="1">Dr</option>
						<option value="2">Cr</option>
					</select> ยอดเงิน <input type="text" name="text_money[]" id="text_money" size="30"  onblur="chk_2fix(this)">					
					
				</div>
			</div>
		</div>
		
		<div id="myDiv">
		
		</div>
		</td></tr>
</table>	
		