function login(){
	$("#login_fail").hide();
	var username = document.frm_signin.username.value;
	var password = document.frm_signin.password.value;
	var remember = $("#remember:checked").val();
  
	if(username==""){
	$("#user-error").fadeIn(700).show("slow").html('<font style="margin-left:30px;color:red;font-size:12px;">\n\กรุณากรอก username</font>');      
	}
	else{
		$("#user-error").fadeOut(700).hide("slow");
	}
 
	if(password==""){
		$("#pass-error").fadeIn(700).show("slow").html('<font style="margin-left:30px;color:red;font-size:12px;">\n\กรุณากรอก password</font>');      
	}
	else {
		$("#pass-error").fadeOut(700).hide("slow");
	}
  
	if(username!="" && password!=""){
		var str = Math.random();
		var datastring = 'str'+str + '&username='+username +'&password='+password+'&remember='+remember;
		$.ajax({
		type:'POST',
		url:'member_login_chk.php',
		data:datastring,
	  
			success:function(data){
				if(data==1){
					window.location.reload();
						//ประยุกต์ใช้ส่วนนี้สั่งโหลด profile ของ member แต่ละคนได้
				}
				else if(data==2){
					$("#login_fail").fadeIn(700).show("slow").html('<font color="white">ท่านถูกระงับการใช้งาน</font>');
					setTimeout(function(){$("#login_fail").fadeOut(700).hide("slow")},5000);
					document.myform.username.value="";
					document.myform.password.value="";
				}
				else if(data==0){
					$("#login_fail").fadeIn(700).show("slow").html('<font color="white">Username หรือ\n\Password ไม่ถูกต้อง</font>');
					setTimeout(function(){$("#login_fail").fadeOut(700).hide("slow")},5000);
					document.myform.username.value="";
					document.myform.password.value="";
				}
			}
		});
	}
}