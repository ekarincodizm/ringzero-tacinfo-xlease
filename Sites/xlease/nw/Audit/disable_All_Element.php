<?php
	function Disable_Element_In_Form($Form_Name)
	{
		?>
		<script>
			//alert("<?php echo $Form_Name; ?>");
			var myForm = '<?php echo $Form_Name; ?>';
				
			var elem = document.getElementById(myForm).elements;
			
        	for(var i = 0; i < elem.length; i++)
        	{
            	elem[i].disabled = true; // ทำให้ แต่ละ Element ไม่สามารถแก้ไขได้ 	
        	} 
		</script>
		
		<?php
	}
?>