<?php
/*
 * ทำการเชื่อมต่อกับ server LDAP และทำการเปลี่ยน Password ของ user ใน LDAP ให้ตรงกับ Password ใน xlease
 * INPUT
 * - UID  email ของ user ที่ตัด @ ออก
 * - Password รหัสผ่านแบบ plain text
 *
 * Krisa Chaijaroen
 * 21/11/2014
 */
 
 //ตัวอย่าง Code ในการเรียกใช้งาน
/*
$uid = "krisa.cha";
$password = "password";

$ldapconn = connect_ldap();
if (isset($ldapconn)) {

	$userdn = search_ldap_user_entry_from_uid($ldapconn, $uid);

	if (isset($userdn)) {
		$pwd_mod = change_ldap_user_password($ldapconn, $userdn, $password);
		if ($pwd_mod) {
			//echo "Password Changed";
		} else {
			$error = ldap_error($ldapconn);
		}
	} else {
		$error = ldap_error($ldapconn);
	}
}
  */

//GLOBAL VAR Base DN ของ LDAP
$basedn = "dc=thaiace,dc=com";


/*
 * ต่อกับ Server LDAP
 * และทำการเซท User ของ LDAP เป็น Admin (Ldap_bind)
 * เพื่อจะได้เปลี่ยน Password ของใครก็ได้
 */
function connect_ldap() {
	$serverip = "172.16.2.110";
	$serverport = 389;
	$username = "cn=admin,dc=thaiace,dc=com";
	$userpassword = "secret";

	$ldapconn = ldap_connect($serverip, $serverport) or die("LDAP Connection Failed!\n");

	if ($ldapconn) {
		ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
		ldap_bind($ldapconn, $username, $userpassword);
		return $ldapconn;
	} else {
		return null;
	}
}

/*
 * ค้นหา user dn จาก uid (krisa.cha) จากฐานข้อมูล LDAP ของ thaiace
 * return -> user dn = uid=krisa.cha,ou=tacinfo,dc=thaiace,dc=com
 */
function search_ldap_user_entry_from_uid($con, $uid) {

	$group_search = ldap_search($con, $GLOBALS['basedn'], "(&(objectclass=person)(uid=" . $uid . "))");

	$group_entry = ldap_first_entry($con, $group_search);
	if($group_entry)
	{
		$userdn = ldap_get_dn($con, $group_entry);
		return $userdn;
	}
	else
	{
		return null;
	}
}

/*
 * เปลี่ยนรหัสผ่านของ user จาก user dn ที่กำหนด
 * และทำการเข้ารหัสเป็นแบบ SHA โดยใช้ base64 encode ข้อมูล sha1 แบบ hex (ตามมาตราฐานการเก็บรหัสใน LDAP)
 */
function change_ldap_user_password($con, $userdn, $newpass) {
	$encoded_newPassword = "{SHA}" . base64_encode(pack("H*", sha1($newpass)));

	$entry = array();
	$entry["userPassword"] = "$encoded_newPassword";

	$pwd_mod = ldap_modify($con, $userdn, $entry);

	return $pwd_mod;
}
?>