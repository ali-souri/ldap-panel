<?php

include 'config.php';



	$projecturl = get_project_url();
	$ldapconnectaddress = get_ldap_connect_address();
	$ldapconnectport = get_ldap_connect_port();
	$adminbindrdn = get_admin_bind_rdn();
	$adminbindpass = get_admin_bind_pass();
	$mainserverrdn = get_main_server_rdn();
	$customadmindn = get_custom_admin_dn();
	$customadminrdn = get_custom_admin_rdn();
	$customadminusername = get_custom_admin_username();
	$customadminpass = get_custom_admin_pass();
	$bindadmindn = get_bind_admin_dn();
	$bindadminrdn = get_bind_admin_rdn();
	$indexpagelocation = get_index_page_location();
	$panelpagelocation = get_panel_page_location();
	$logoutpagelocation = get_logout_page_location();	
	$node1rdn = get_node1_rdn();
	$node1usablerdn = get_node1_usable_rdn();
	$zarafauserserver = get_zarafauserserver();
	$oustar = get_ou_star(); 
	$secparenthelper = explode($mainserverrdn, ",");
	$pos = strpos($mainserverrdn, ',', 0);
	$secparentan = substr($mainserverrdn, 0 , $pos);
	$secparent = $secparenthelper[0];

header('Content-type: application/json');

function get_name_from_dn($insert_dn){
	$explode1 = explode("," , $insert_dn);
	 return substr($explode1[0], 3);
}

 
function get_parent_from_dn($inserted_dn){
	$explode2 = explode("," , $inserted_dn);
	return $explode2[1];
}

$ds=ldap_connect( $ldapconnectaddress, $ldapconnectport );
ldap_set_option(NULL, LDAP_OPT_DEBUG_LEVEL, 7);
                        ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION,3);
                        ldap_set_option($ds, LDAP_OPT_REFERRALS,0);

if ($ds) {

	$r=ldap_bind($ds, $adminbindrdn, $adminbindpass);
	if ($r) {
	 
	
	$sr=ldap_search($ds, $mainserverrdn, $oustar);
	if ($sr) {
	
	$info = ldap_get_entries($ds, $sr);
	 $id = 0;
	 $json_last_array = Array();
//var_dump($info);
function get_parent_id($insert_string){
	global $json_last_array;
	$explode = explode("," , $insert_string);
	foreach ($json_last_array as $key => $val) {
       if ($val["text"] === $explode[1]) {
           return $val["id"];
       }
   };
   return "#";
}

 
unset($info[0]);	 
	 foreach ($info as $key => $value) {
	 	 if ($key!=="count") {	 	 
	 	 	// 	 if (($id===0)&&(get_name_from_dn($value["dn"])==="cn=admin")) {
	 			//  	$json_last_array[0] = Array ( "id" => 0 ,"parent" => "#" ,"text" => "dc=elenoon,dc=ir" ,"icon" => "img/ldap-o.png" ,"state" => Array ( "selected" => 1 ,"opened" => 1 ) );
	 			//  	$json_last_array[1] = Array ( "id" => 1 ,"parent" => 0 ,"text" => "cn=admin" ,"icon" => "img/ldap-uid.png" ) ;
	 			// 	$id = $id + 2;
	 			// } else{
	 				if (get_parent_from_dn($value["dn"])===$secparentan) {	
	 				$json_last_array[$id] = Array ( "id" => "0" ,"parent" => "#" ,"text" => "node1" ,"icon" => "img/ldap-ou.png" , "state" => Array ( "selected" => 1 ,"opened" => 1 ) ); 
	 	 			$id++;
	 	 			}else{
	 	 			$json_last_array[$id] = Array ( "id" => $id ,"parent" => "0" ,"text" => get_name_from_dn($value["dn"]) ,"icon" => "img/ldap-ou.png"); 
	 	 			$id++;
	 	 			}
	 	 		//}
	 	 }else{
	 	 	continue;
	 	 }
	}

	echo json_encode($json_last_array);

	ldap_close($ds);
}
}
} else {
	echo "<h4>Unable to connect to LDAP server</h4>";
}

?>
