<?php
include("../config/config.php");
$regis = $_GET['regis'];
$lastidno = $_GET['lastidno'];
$nowdate = nowDate();

$qry_un=pg_query("select * from \"UNContact\" WHERE (\"IDNO\" = '$lastidno')");
if($res_un=pg_fetch_array($qry_un)){
    $C_CARNUM=$res_un["C_CARNUM"];
    $CusID=$res_un["CusID"];
    $asset_id=$res_un["asset_id"];
}
?>

<script type="text/javascript">
$(document).ready(function(){

    readonly(1);
    
    $("#add_dateidcard").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
    $("#signdate").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
    $("#startdate").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
    
    $("#name").autocomplete({
        source: "create_cus_part_change_cuslist.php",
        select : function(event, ui){
            readonly(1);
        },
        close : function(event, ui){
            $.post('create_cus_part_change_cusdetail.php',{
                cid: $('#name').val()
            },
            function(data){
                if(data.success){
                    $('#add_firstname').val(data.firstname);
                    $('#add_name').val(data.name);
                    $('#add_surname').val(data.surname);
                    $('#add_reg').val(data.reg);
                    $('#add_birthdate').val(data.birthdate);
                    $('#add_pair').val(data.pair);
                    $('#add_card').val(data.card);
                    $('#add_address').val(data.address);
                    $('#add_idcard').val(data.idcard);
                    $('#add_moo').val(data.moo);
                    $('#add_dateidcard').val(data.dateidcard);
                    $('#add_soi').val(data.soi);
                    $('#add_bycard').val(data.bycard);
                    $('#add_road').val(data.road);
                    $('#add_contactadd').val(data.contactadd);
                    $('#add_tambon').val(data.tambon);
                    $('#add_ampur').val(data.ampur);
                    $("#add_province option:selected").text(data.province);
                }else{

                }
            },'json');

        }
    });
    
    $('#btnaddnewcus').click(function(){
        readonly(2);
        $("#name").val("");
        $("#add_firstname").focus();
        $('#add_firstname').val('');
        $('#add_name').val('');
        $('#add_surname').val('');
        $('#add_reg').val('');
        $('#add_birthdate').val('');
        $('#add_pair').val('');
        $('#add_card').val('');
        $('#add_address').val('');
        $('#add_idcard').val('');
        $('#add_moo').val('');
        $('#add_dateidcard').val('<?php echo $nowdate; ?>');
        $('#add_soi').val('');
        $('#add_bycard').val('');
        $('#add_road').val('');
        $('#add_contactadd').val('');
        $('#add_tambon').val('');
        $('#add_ampur').val('');
        $("#add_province option:selected").text('เลือก');
    });

    $('#btnsave').click(function(){
        $.post('create_cus_part_change_save.php',{
            name: $('#name').val(),
            add_firstname: $('#add_firstname').val(),
            add_name: $('#add_name').val(),
            add_surname: $('#add_surname').val(),
            add_reg: $('#add_reg').val(),
            add_birthdate: $('#add_birthdate').val(),
            add_pair: $('#add_pair').val(),
            add_card: $('#add_card').val(),
            add_address: $('#add_address').val(),
            add_idcard: $('#add_idcard').val(),
            add_moo: $('#add_moo').val(),
            add_dateidcard: $('#add_dateidcard').val(),
            add_soi: $('#add_soi').val(),
            add_bycard: $('#add_bycard').val(),
            add_road: $('#add_road').val(),
            add_contactadd: $('#add_contactadd').val(),
            add_tambon: $('#add_tambon').val(),
            add_ampur: $('#add_ampur').val(),
            add_province: $('#add_province').val(),
            signdate: $('#signdate').val(),
            startdate: $('#startdate').val(),
            typecontact: $('#typecontact').val(),
            carnum: '<?php echo $C_CARNUM; ?>',
            cusid: '<?php echo $CusID; ?>',
            carid: '<?php echo $asset_id; ?>',
            idno: '<?php echo $lastidno; ?>'
        },
        function(data){
            if(data.success){
                $("#panel").html('<center>บันทึกเรียบร้อยแล้ว</center>');
            }else{
                alert(data.message);
            }
        },'json');
    });
    
});

function readonly(status){
    if(status == 1){
        $("#add_firstname").attr("readonly","true"); $("#add_firstname").attr("style","background-color:#E0E0E0");
        $("#add_name").attr("readonly","true"); $("#add_name").attr("style","background-color:#E0E0E0");
        $("#add_surname").attr("readonly","true"); $("#add_surname").attr("style","background-color:#E0E0E0");
        $("#add_pair").attr("readonly","true"); $("#add_pair").attr("style","background-color:#E0E0E0");
        $("#add_address").attr("readonly","true"); $("#add_address").attr("style","background-color:#E0E0E0");
        $("#add_moo").attr("readonly","true"); $("#add_moo").attr("style","background-color:#E0E0E0");
        $("#add_soi").attr("readonly","true"); $("#add_soi").attr("style","background-color:#E0E0E0");
        $("#add_road").attr("readonly","true"); $("#add_road").attr("style","background-color:#E0E0E0");
        $("#add_tambon").attr("readonly","true"); $("#add_tambon").attr("style","background-color:#E0E0E0");
        $("#add_ampur").attr("readonly","true"); $("#add_ampur").attr("style","background-color:#E0E0E0");
        $("#add_province").attr("disabled","disabled"); $("#add_province").attr("style","background-color:#E0E0E0");
        $("#add_reg").attr("readonly","true"); $("#add_reg").attr("style","background-color:#E0E0E0");
        $("#add_birthdate").attr("readonly","true"); $("#add_birthdate").attr("style","background-color:#E0E0E0");
        $("#add_card").attr("readonly","true"); $("#add_card").attr("style","background-color:#E0E0E0");
        $("#add_idcard").attr("readonly","true"); $("#add_idcard").attr("style","background-color:#E0E0E0");
        $("#add_dateidcard").attr("readonly","true"); $("#add_dateidcard").attr("style","background-color:#E0E0E0");
        $("#add_bycard").attr("readonly","true"); $("#add_bycard").attr("style","background-color:#E0E0E0");
        $("#add_contactadd").attr("readonly","true"); $("#add_contactadd").attr("style","background-color:#E0E0E0");
    }else{
        $("#add_firstname").attr("readonly",""); $("#add_firstname").attr("style","background-color:#E8FFE8");
        $("#add_name").attr("readonly",""); $("#add_name").attr("style","background-color:#E8FFE8");
        $("#add_surname").attr("readonly",""); $("#add_surname").attr("style","background-color:#E8FFE8");
        $("#add_pair").attr("readonly",""); $("#add_pair").attr("style","background-color:#E8FFE8");
        $("#add_address").attr("readonly",""); $("#add_address").attr("style","background-color:#E8FFE8");
        $("#add_moo").attr("readonly",""); $("#add_moo").attr("style","background-color:#E8FFE8");
        $("#add_soi").attr("readonly",""); $("#add_soi").attr("style","background-color:#E8FFE8");
        $("#add_road").attr("readonly",""); $("#add_road").attr("style","background-color:#E8FFE8");
        $("#add_tambon").attr("readonly",""); $("#add_tambon").attr("style","background-color:#E8FFE8");
        $("#add_ampur").attr("readonly",""); $("#add_ampur").attr("style","background-color:#E8FFE8");
        $("#add_province").attr("disabled",""); $("#add_province").attr("style","background-color:#E8FFE8");
        $("#add_reg").attr("readonly",""); $("#add_reg").attr("style","background-color:#E8FFE8");
        $("#add_birthdate").attr("readonly",""); $("#add_birthdate").attr("style","background-color:#E8FFE8");
        $("#add_card").attr("readonly",""); $("#add_card").attr("style","background-color:#E8FFE8");
        $("#add_idcard").attr("readonly",""); $("#add_idcard").attr("style","background-color:#E8FFE8");
        $("#add_dateidcard").attr("readonly",""); $("#add_dateidcard").attr("style","background-color:#E8FFE8");
        $("#add_bycard").attr("readonly",""); $("#add_bycard").attr("style","background-color:#E8FFE8");
        $("#add_contactadd").attr("readonly",""); $("#add_contactadd").attr("style","background-color:#E8FFE8");
    }
}
</script>    

<style type="text/css">
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:13px;
    text-align:center;
}
</style>

<div class="graybox" style="margin-top:10px">

<div align="right" style="margin-bottom:10px"><b>ค้นหาชื่อลูกค้า</b> <input id="name" name="name" size="25" /> / <input type="button" id="btnaddnewcus" value="เพิ่มลูกค้าใหม่"/></div>
<div style="clear:both"></div>

<div style="float:left; width:380px">
<table width="100%" cellpadding="3" cellspacing="0" border="0">
    <tr>
        <td width="100"><b>คำนำหน้าชื่อ</b></td>
        <td><input type="text" id="add_firstname" name="add_firstname" size="10"></td>
    </tr>
    <tr>
        <td><b>ชื่อ</b></td>
        <td><input type="text" id="add_name" name="add_name" size="13"> <b>สกุล</b> <input type="text" id="add_surname" name="add_surname" size="13"></td>
    </tr>
    <tr>
        <td><b>ชื่อ คู่สมรส</b></td>
        <td><input type="text" id="add_pair" name="add_pair" value=""></td>
    </tr>
    <tr>
        <td><b>เลขที่</b></td>
        <td><input type="text" id="add_address" name="add_address" value=""></td>
    </tr>
    <tr>
        <td><b>หมู่ที่</b></td>
        <td><input type="text" id="add_moo" name="add_moo" value=""></td>
    </tr>
    <tr>
        <td><b>ซอย</b></td>
        <td><input type="text" id="add_soi" name="add_soi" value=""></td>
    </tr>
    <tr>
        <td><b>ถนน</b></td>
        <td><input type="text" id="add_road" name="add_road" value=""></td>
    </tr>
    
    <tr>
        <td><b>แขวง/ตำบล</b></td>
        <td><input type="text" id="add_tambon" name="add_tambon" value=""></td>
    </tr>
    <tr>
        <td><b>เขต/อำเภอ</b></td>
        <td><input type="text" id="add_ampur" name="add_ampur" value=""></td>
    </tr>
    <tr>
        <td><b>จังหวัด</b></td>
        <td>
        <select id="add_province" name="add_province">
        <option value="">เลือก</option>
        <option value="กระบี่">กระบี่</option>
        <option value="กรุงเทพ">กรุงเทพมหานคร</option>
        <option value="กาญจนบุรี">กาญจนบุรี</option>
        <option value="กาฬสินธุ์">กาฬสินธุ์</option>
        <option value="กำแพงเพชร">กำแพงเพชร</option>
        <option value="ขอนแก่น">ขอนแก่น</option>
        <option value="จันทบุรี">จันทบุรี</option>
        <option value="ฉะเชิงเทรา">ฉะเชิงเทรา</option>
        <option value="ชลบุรี">ชลบุรี</option>
        <option value="ชัยนาท">ชัยนาท</option>
        <option value="ชัยภูมิ">ชัยภูมิ</option>
        <option value="ชุมพร">ชุมพร</option>
        <option value="เชียงราย">เชียงราย</option>
        <option value="เชียงใหม่">เชียงใหม่</option>
        <option value="ตรัง">ตรัง</option>
        <option value="ตราด">ตราด</option>
        <option value="ตาก">ตาก</option>
        <option value="นครนายก">นครนายก</option>
        <option value="นครปฐม">นครปฐม</option>
        <option value="นครพนม">นครพนม</option>
        <option value="นครราชสีมา">นครราชสีมา</option>
        <option value="นครศรีธรรมราช">นครศรีธรรมราช</option>
        <option value="นครสวรรค์">นครสวรรค์</option>
        <option value="นนทบุรี">นนทบุรี</option>
        <option value="นราธิวาส">นราธิวาส</option>
        <option value="น่าน">น่าน</option>
        <option value="บุรีรัมย์">บุรีรัมย์</option>
		<option value="บึงกาฬ">บึงกาฬ</option>
        <option value="ปทุมธานี">ปทุมธานี</option>
        <option value="ประจวบคีรีขันธ์">ประจวบคีรีขันธ์</option>
        <option value="ปราจีนบุรี">ปราจีนบุรี</option>
        <option value="ปัตตานี">ปัตตานี</option>
        <option value="พระนครศรีอยุธยา">พระนครศรีอยุธยา</option>
        <option value="พะเยา">พะเยา</option>
        <option value="พังงา">พังงา</option>
        <option value="พัทลุง">พัทลุง</option>
        <option value="พิจิตร">พิจิตร</option>
        <option value="พิษณุโลก">พิษณุโลก</option>
        <option value="เพชรบุรี">เพชรบุรี</option>
        <option value="เพชรบูรณ์">เพชรบูรณ์</option>
        <option value="แพร่">แพร่</option>
        <option value="ภูเก็ต">ภูเก็ต</option>
        <option value="มหาสารคาม">มหาสารคาม</option>
        <option value="มุกดาหาร">มุกดาหาร</option>
        <option value="แม่ฮ่องสอน">แม่ฮ่องสอน</option>
        <option value="ยโสธร">ยโสธร</option>
        <option value="ยะลา">ยะลา</option>
        <option value="ร้อยเอ็ด">ร้อยเอ็ด</option>
        <option value="ระนอง">ระนอง</option>
        <option value="ระยอง">ระยอง</option>
        <option value="ราชบุรี">ราชบุรี</option>
        <option value="ลพบุรี">ลพบุรี</option>
        <option value="ลำปาง">ลำปาง</option>
        <option value="ลำพูน">ลำพูน</option>
        <option value="เลย">เลย</option>
        <option value="ศรีษะเกษ">ศรีษะเกษ</option>
        <option value="สกลนคร">สกลนคร</option>
        <option value="สงขลา">สงขลา</option>
        <option value="สตูล">สตูล</option>
        <option value="สมุทรปราการ">สมุทรปราการ</option>
        <option value="สมุทรสงคราม">สมุทรสงคราม</option>
        <option value="สมุทรสาคร">สมุทรสาคร</option>
        <option value="สระแก้ว">สระแก้ว</option>
        <option value="สระบุรี">สระบุรี</option>
        <option value="สิงห์บุรี">สิงห์บุรี</option>
        <option value="สุโขท้ย">สุโขท้ย</option>
        <option value="สุพรรณบุรี">สุพรรณบุรี</option>
        <option value="สุราษฎร์ธานี">สุราษฎร์ธานี</option>
        <option value="สุรินทร์">สุรินทร์</option>
        <option value="หนองคาย">หนองคาย</option>
        <option value="หนองบัวลำภู">หนองบัวลำภู</option>
        <option value="อ่างทอง">อ่างทอง</option>
        <option value="อำนาจเจริญ">อำนาจเจริญ</option>
        <option value="อุดรธานี">อุดรธานี</option>
        <option value="อุตรดิตถ์">อุตรดิตถ์</option>
        <option value="อุทัยธานี">อุทัยธานี</option>
        <option value="อุบลราชธานี">อุบลราชธานี</option>
        </select>        
        </td>
    </tr>
</table>
</div>
<div style="float:left; width:380px">
<table width="100%" cellpadding="3" cellspacing="0" border="0">
    <tr>
        <td width="100"><b>สัญชาติ</b></td>
        <td><input type="text" id="add_reg" name="add_reg" value="ไทย"></td>
    </tr>
    <tr>
        <td><b>อายุ</b></td>
        <td><input type="text" id="add_birthdate" name="add_birthdate" value=""></td>
    </tr>
    <tr>
        <td><b>บัตรแสดงตัว</b></td>
        <td><input type="text" id="add_card" name="add_card" value=""></td>
    </tr>
    <tr>
        <td><b>เลขที่บัตร</b></td>
        <td><input type="text" id="add_idcard" name="add_idcard" value=""></td>
    </tr>
    <tr>
        <td><b>วันที่ออกบัตร</b></td>
        <td><input type="text" id="add_dateidcard" name="add_dateidcard" value="<?php echo $nowdate; ?>" size="15" style="text-align:center"></td>
    </tr>
    <tr>
        <td><b>ออกให้โดย</b></td>
        <td><input type="text" id="add_bycard" name="add_bycard" value=""></td>
    </tr>
    <tr>
        <td><b>ที่ติดต่อ</b></td>
        <td><textarea id="add_contactadd" name="add_contactadd" rows="4" cols="35"></textarea></td>
    </tr>
</table>
</div>
<div style="clear:both"></div>

<div>
<hr />
<table cellpadding="3" cellspacing="0" border="0" width="100%">
<tr>
    <td width="100"><b>วันที่ทำสัญญา</b></td>
    <td><input type="text" id="signdate" name="signdate" value="<?php echo $nowdate; ?>" size="15" style="text-align:center"></td>
</tr>
<tr>
    <td><b>วันที่งวดแรก</b></td>
    <td><input type="text" id="startdate" name="startdate" value="<?php echo $nowdate; ?>" size="15" style="text-align:center"></td>
</tr>
<tr>
    <td><b>รูปแบบการชำระ</b></td>
    <td>
<select name="typecontact" id="typecontact">
<?php
$qry_type=pg_query("select * from corporate.\"type_corp\" ORDER BY \"contact_code\"");
while($res_type=pg_fetch_array($qry_type)){
    $contact_code=$res_type["contact_code"];
    $dtl_code=$res_type["dtl_code"];
    echo "<option value=\"$contact_code\">$contact_code, $dtl_code</option>";
}
?>
</select>
    </td>
</tr>
</table>
</div>

</div>

<div align="right"><input type="button" name="btnsave" id="btnsave" value="บันทึก" class="ui-button"></div>