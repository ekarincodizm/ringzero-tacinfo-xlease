<?php 
include("../../config/config.php");


$cmd = $_GET['cmd'];
$set_tax_id = $_GET['id'];

$app_date = Date('Y-m-d H:i:s');

			$nub3=0;
	if($cmd=="edit"){
				
						           $qry_fr3=pg_query("SELECT tax_begin, tax_end, tax_rate, tax_percent, tax_step, tax_max
	   FROM \"hr_payroll_tax\" where id = '$set_tax_id' ");

			$nub3=pg_num_rows($qry_fr3); 
			$s_c =0;
			
	}
				
			?>
            <input type="button" name="btn_add" id="btn_add" value="+ เพิ่ม"><input type="button" name="btn_del" id="btn_del" value="- ลบ">
<table class="t2" id="root1"  width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" >
			
			<tr style="font-weight:bold;" valign="middle" align="center"  bgcolor="#79BCFF" >
         
				<td align="right">ขั้นเงินได้สุทธิตั้งแต่</td>
                <td align="right">ขั้นเงินได้สุทธิถึง</td>
                <td align="right">ช่วงเงินได้สุทธิ</td>
                <td align="right">อัตราภาษีร้อยละ</td>
                <td align="right">ภาษีแต่ละขั้นเงินได้</td>
                
                <td align="right">ภาษีสะสมสูงสุดของขั้น</td>
                
                 </tr>
				<?php 
				
				
     
			if($nub3>0){
			while($sql_row43=pg_fetch_array($qry_fr3)){

					$tax_begin = $sql_row43['tax_begin'];
					$tax_end = $sql_row43['tax_end'];
					$tax_rate = $sql_row43['tax_rate'];
					$tax_percent = $sql_row43['tax_percent'];
					$tax_step = $sql_row43['tax_step'];
					$tax_max = $sql_row43['tax_max'];
					$s_c++;
					
			?>

                
                <tr style="font-weight:bold;" valign="middle" align="center" id="tr<?php echo $s_c ?>" >
                <td align="right"><input type="text" name="tax_begin<?php echo $s_c ?>" id="tax_begin<?php echo $s_c ?>" style="text-align:right" size="15" onkeyup="dokeyup(this,event);" onchange="dokeyup(this,event);" onkeypress="checknumber(event)"  value="<?php echo number_format($tax_begin,2); ?>" ></td>

                <td align="right"><input type="text" name="tax_end<?php echo $s_c ?>" id="tax_end<?php echo $s_c ?>" size="15"  style="text-align:right" onkeyup="dokeyup(this,event);" onchange="dokeyup(this,event);" onkeypress="checknumber(event)"   value="<?php echo number_format($tax_end,2); ?>" /></td>

                <td align="right"><input type="text" name="tax_rate<?php echo $s_c ?>" id="tax_rate<?php echo $s_c ?>" size="15"  style="text-align:right" onkeyup="dokeyup(this,event);" onchange="dokeyup(this,event);" onkeypress="checknumber(event)"   value="<?php echo number_format($tax_rate,2); ?>" /></td>

                <td align="right"><input type="text" name="tax_percent<?php echo $s_c ?>" id="tax_percent<?php echo $s_c ?>" size="15"  style="text-align:right" onkeyup="dokeyup(this,event);" onchange="dokeyup(this,event);" onkeypress="checknumber(event)"   value="<?php echo number_format($tax_percent,2); ?>" /></td>

                <td align="right"><input type="text" name="tax_step<?php echo $s_c ?>" id="tax_step<?php echo $s_c ?>" size="15"  style="text-align:right" onkeyup="dokeyup(this,event);" onchange="dokeyup(this,event);" onkeypress="checknumber(event)"  value="<?php echo number_format($tax_step,2); ?>" /></td>
                 <td align="right"><input type="text" name="tax_max<?php echo $s_c ?>" id="tax_max<?php echo $s_c ?>" size="15"  style="text-align:right" onkeyup="dokeyup(this,event);" onchange="dokeyup(this,event);" onkeypress="checknumber(event)"  value="<?php echo number_format($tax_max,2); ?>" /></td>
          </tr>
			<?php 		}

			}
	 ?>


			</table><div style="float:right"><button id="btnSubmit" name="btnSubmit" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all">
  <?Php if($cmd=="add")echo "เพิ่ม" ; else if($cmd=="edit")echo "แก้ไข"; ?>
</button> </div>

<script type="text/javascript">


var counter = <?php echo $nub3  ?>;

$('#btn_add').click(function(){
    counter++;
 

   var tr = '<tr style="font-weight:bold;" valign="middle" align="center" id="tr'+counter+'"><td align="right"><input type="text" name="tax_begin'+counter+'" id="tax_begin'+counter+'" style="text-align:right" size="15" onkeyup="dokeyup(this,event);" onchange="dokeyup(this,event);" onkeypress="checknumber(event)"  value="" ></td><td align="right"><input type="text" name="tax_end'+counter+'" id="tax_end'+counter+'" size="15"  style="text-align:right" onkeyup="dokeyup(this,event);" onchange="dokeyup(this,event);" onkeypress="checknumber(event)"   value="" /></td><td align="right"><input type="text" name="tax_rate'+counter+'" id="tax_rate'+counter+'" size="15"  style="text-align:right" onkeyup="dokeyup(this,event);" onchange="dokeyup(this,event);" onkeypress="checknumber(event)"   value="" /></td><td align="right"><input type="text" name="tax_percent'+counter+'" id="tax_percent'+counter+'" size="15"  style="text-align:right" onkeyup="dokeyup(this,event);" onchange="dokeyup(this,event);" onkeypress="checknumber(event)"   value="" /></td><td align="right"><input type="text" name="tax_step'+counter+'" id="tax_step'+counter+'" size="15"  style="text-align:right" onkeyup="dokeyup(this,event);" onchange="dokeyup(this,event);" onkeypress="checknumber(event)"  value="" /></td><td align="right"><input type="text" name="tax_max'+counter+'" id="tax_max'+counter+'" size="15"  style="text-align:right" onkeyup="dokeyup(this,event);" onchange="dokeyup(this,event);" onkeypress="checknumber(event)"  value="" /></td></tr>';
 //$('#root1').append(tr);
// var newRow = $("<tr><td>hi</td></tr>");
  // $("#root1").append(newRow);
//alert(1);
    $('#root1 tr:last').after(tr);

});

$("#btn_del").click(function(){
    if(counter==1 || counter==0){
        return false;
    }
    $("#root1 tr:last").remove();  
    counter--;
   
});
$('#btnSubmit').click(function(){
        if(counter==0){
        return false;
    }
    var arradd = [];
    for( i=1; i<=counter; i++ ){
		
        var cc = $('#tax_begin'+ i).val();
        var uu = $('#tax_end'+ i).val();
        var pp = $('#tax_rate'+ i).val();
        var vv = $('#tax_percent'+ i).val();
        var ss = $('#tax_step'+ i).val();
		var zz = $('#tax_max'+ i).val();
       
        if(cc == ""){
            cc="0";
            
        }
        if(uu == "" ){
           uu = "0";
        }
        if(pp == "" ){
            pp = "0";
           
        }
        if (vv == ""){
            vv = "0";
            
        }
        if(ss == ""){
            ss = "0"
        }
		if(zz == ""){
            zz = "0"
        }
        arradd[i-1] =  { tax_begin:cc, tax_end:uu, tax_rate:pp, tax_percent:vv, tax_step:ss, tax_max:zz };
    }

    $.post('tax_setting_api.php',{
        cmd: '<?php echo  $cmd ?>',
        set_tax_id :<?php echo  $set_tax_id ?>,
        arradd: JSON.stringify(arradd)
    },
    function(data){
        if(data.success){
        		alert("บันทึกรายการเรียบร้อย");
				$("#panel").load("tax_setting_dtl.php");
				 $('#dialog').remove();
			
        }else{
            alert(data.message);
        }
    },'json');
});
  $("button").button();

	


</script>