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




	$ou = $_REQUEST['ou'];

header('Content-type: application/json');


	$search_ou = $ou . $node1usablerdn;

$ds=ldap_connect( $ldapconnectaddress, $ldapconnectport );
ldap_set_option(NULL, LDAP_OPT_DEBUG_LEVEL, 7);
                        ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION,3);
                        ldap_set_option($ds, LDAP_OPT_REFERRALS,0);

if ($ds) {

	$r=ldap_bind($ds, $adminbindrdn , $adminbindpass);
	if ($r) {
	 
	
	$sr=ldap_search($ds, $search_ou , "uid=*");

	if ($sr) {
	
	$info = ldap_get_entries($ds, $sr);

	 $id = 0;
	 $json_last_array = Array();


	 foreach ($info as $key => $value) {
	 	 if ($key!=="count") {
	 	 	$explode = explode("," , $info[$id]["dn"]);
	 	 	$last_explode = $explode[0] . "," .$explode[1];
	 		$helper_array = Array("mobile" => $info[$id]["mobile"][0] , "address" => $info[$id]["mail"][0] , "name" => $info[$id]["cn"][0] , "ou" => $last_explode);
 	 			$json_last_array[$id+1] = $helper_array;
 	 			$id++;
	 	 	}
	 	}
	 

//Array ( [count] => 3 
	// [0] => Array ( [cn] => Array ( [count] => 1 [0] => first ) [0] => cn [gidnumber] => Array ( [count] => 1 [0] => 100009 ) [1] => gidnumber [homedirectory] => Array ( [count] => 1 [0] => /home/9809121452949100009 ) [2] => homedirectory [mail] => Array ( [count] => 1 [0] => eghbal@fci.co.ir ) [3] => mail [mobile] => Array ( [count] => 1 [0] => 09121452949 ) [4] => mobile [objectclass] => Array ( [count] => 4 [0] => posixAccount [1] => top [2] => zarafa-user [3] => inetOrgPerson ) [5] => objectclass [sn] => Array ( [count] => 1 [0] => 09121452949 ) [6] => sn [uid] => Array ( [count] => 1 [0] => eghbal@fci.co.ir ) [7] => uid [uidnumber] => Array ( [count] => 1 [0] => 9809121452949100009 ) [8] => uidnumber [userpassword] => Array ( [count] => 1 [0] => {ssha}AhyIomXd_T ) [9] => userpassword [zarafaaccount] => Array ( [count] => 1 [0] => 1 ) [10] => zarafaaccount [zarafaadmin] => Array ( [count] => 1 [0] => 0 ) [11] => zarafaadmin [zarafaalertsms] => Array ( [count] => 1 [0] => 1 ) [12] => zarafaalertsms [zarafaaliases] => Array ( [count] => 1 [0] => 09121452949@fci.co.ir ) [13] => zarafaaliases [zarafaconfirm] => Array ( [count] => 1 [0] => 1 ) [14] => zarafaconfirm [zarafaenabledfeatures] => Array ( [count] => 2 [0] => imap [1] => pop3 ) [15] => zarafaenabledfeatures [zarafahidden] => Array ( [count] => 1 [0] => 1 ) [16] => zarafahidden [zarafaquotahard] => Array ( [count] => 1 [0] => 1900 ) [17] => zarafaquotahard [zarafaquotaoverride] => Array ( [count] => 1 [0] => 1 ) [18] => zarafaquotaoverride [zarafaquotasoft] => Array ( [count] => 1 [0] => 1800 ) [19] => zarafaquotasoft [zarafaquotawarn] => Array ( [count] => 1 [0] => 1700 ) [20] => zarafaquotawarn [zarafasharedstoreonly] => Array ( [count] => 1 [0] => 0 ) [21] => zarafasharedstoreonly [zarafauserserver] => Array ( [count] => 1 [0] => 192.168.0.22 ) [22] => zarafauserserver [count] => 23 [dn] => uid=eghbal@fci.co.ir,ou=tehran,ou=node1,dc=elenoon,dc=ir )
	// [1] => Array ( [cn] => Array ( [count] => 1 [0] => man ) [0] => cn [gidnumber] => Array ( [count] => 1 [0] => 100009 ) [1] => gidnumber [homedirectory] => Array ( [count] => 1 [0] => /home/9809124405912100009 ) [2] => homedirectory [mail] => Array ( [count] => 1 [0] => vahabi@fci.co.ir ) [3] => mail [mobile] => Array ( [count] => 1 [0] => 09124405912 ) [4] => mobile [objectclass] => Array ( [count] => 4 [0] => posixAccount [1] => top [2] => zarafa-user [3] => inetOrgPerson ) [5] => objectclass [sn] => Array ( [count] => 1 [0] => chokh ) [6] => sn [uid] => Array ( [count] => 1 [0] => vahabi@fci.co.ir ) [7] => uid [uidnumber] => Array ( [count] => 1 [0] => 9809124405912100009 ) [8] => uidnumber [userpassword] => Array ( [count] => 1 [0] => 123456 ) [9] => userpassword [zarafaaccount] => Array ( [count] => 1 [0] => 1 ) [10] => zarafaaccount [zarafaadmin] => Array ( [count] => 1 [0] => 0 ) [11] => zarafaadmin [zarafaalertsms] => Array ( [count] => 1 [0] => 0 ) [12] => zarafaalertsms [zarafaaliases] => Array ( [count] => 1 [0] => 09124405912@fci.co.ir ) [13] => zarafaaliases [zarafaconfirm] => Array ( [count] => 1 [0] => 1 ) [14] => zarafaconfirm [zarafaenabledfeatures] => Array ( [count] => 2 [0] => imap [1] => pop3 ) [15] => zarafaenabledfeatures [zarafahidden] => Array ( [count] => 1 [0] => 1 ) [16] => zarafahidden [zarafaquotahard] => Array ( [count] => 1 [0] => 1900 ) [17] => zarafaquotahard [zarafaquotaoverride] => Array ( [count] => 1 [0] => 1 ) [18] => zarafaquotaoverride [zarafaquotasoft] => Array ( [count] => 1 [0] => 1800 ) [19] => zarafaquotasoft [zarafaquotawarn] => Array ( [count] => 1 [0] => 1700 ) [20] => zarafaquotawarn [zarafasharedstoreonly] => Array ( [count] => 1 [0] => 0 ) [21] => zarafasharedstoreonly [zarafauserserver] => Array ( [count] => 1 [0] => 192.168.0.22 ) [22] => zarafauserserver [count] => 23 [dn] => uid=vahabi@fci.co.ir,ou=tehran,ou=node1,dc=elenoon,dc=ir ) 
	// [2] => Array ( [zarafaadmin] => Array ( [count] => 1 [0] => 0 ) [0] => zarafaadmin [objectclass] => Array ( [count] => 4 [0] => simpleSecurityObject [1] => zarafa-user [2] => inetOrgPerson [3] => top ) [1] => objectclass [givenname] => Array ( [count] => 1 [0] => ali ) [2] => givenname [zarafaquotawarn] => Array ( [count] => 1 [0] => 1 ) [3] => zarafaquotawarn [zarafaquotasoft] => Array ( [count] => 1 [0] => 1 ) [4] => zarafaquotasoft [cn] => Array ( [count] => 1 [0] => souri ) [5] => cn [zarafaquotahard] => Array ( [count] => 1 [0] => 100 ) [6] => zarafaquotahard [uid] => Array ( [count] => 1 [0] => alisouri ) [7] => uid [mail] => Array ( [count] => 1 [0] => alisouri@fci.co.ir ) [8] => mail [zarafaquotaoverride] => Array ( [count] => 1 [0] => 12 ) [9] => zarafaquotaoverride [userpassword] => Array ( [count] => 1 [0] => 123 ) [10] => userpassword [telephonenumber] => Array ( [count] => 1 [0] => 09127975061 ) [11] => telephonenumber [mobile] => Array ( [count] => 1 [0] => 09127975061 ) [12] => mobile [sn] => Array ( [count] => 1 [0] => souri ) [13] => sn [count] => 14 [dn] => cn=souri,ou=tehran,ou=node1,dc=elenoon,dc=ir ) )



//Array ( [0] => Array ( [mobile] => 09129797979 [address] => ali@ali [name] => alisouri [action] => dn=alisouri ) 
	   // [1] => Array ( [mobile] => 09129797979 [address] => ali@ali [name] => alisouri [action] => dn=alisouri ) 
       // [2] => Array ( [mobile] => 09129797979 [address] => ali@ali [name] => alisouri [action] => dn=alisouri ) 
	   // [3] => Array ( [mobile] => 09129797979 [address] => ali@ali [name] => alisouri [action] => dn=alisouri ) 
	   // [4] => Array ( [mobile] => 09129797979 [address] => ali@ali [name] => alisouri [action] => dn=alisouri ) ) 


	 // echo "<div style='white-space:pre;'>";
	 // //print_r($info);
	 //  print_r($json_last_array);
	 // echo "</div>";



	echo json_encode($json_last_array);

	ldap_close($ds);
}
}
} else {
	echo "<h4>Unable to connect to LDAP server</h4>";
}


	?>

