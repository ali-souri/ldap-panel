<?php
	
	//---------project variables-----------

	$project_url = "merto.ir";
	$ldap_connect_address = "127.0.0.1" ;
	$ldap_connect_port = 389;
	$admin_bind_rdn = "cn=admin,dc=elenoon,dc=ir";
	$admin_bind_pass = "asro@evara@vera";
	//$admin_bind_rdn = "cn=customadmin,dc=elenoon,dc=ir";
	//$admin_bind_pass = "qazwsx";
	$main_server_rdn = "dc=elenoon,dc=ir";
	$custom_admin_dn = "cn=customadmin";
	$custom_admin_rdn = "cn=customadmin,dc=elenoon,dc=ir";
	$custom_admin_username = "customadmin";
	$custom_admin_pass = "qazwsx";
	$bind_admin_dn = "cn=admin";
	$bind_admin_rdn = "cn=admin,dc=elenoon,dc=ir";
	$index_page_location = "/ldap-panel/index.php";
	$panel_page_location = "/ldap-panel/panel.php";
	$logout_page_location = "/ldap-panel/logout.php";	
	$node1_rdn = "ou=node1,dc=elenoon,dc=ir";
	$node1_usable_rdn = ",ou=node1,dc=elenoon,dc=ir";
	$zarafauserserver = "192.168.0.22";
	$ou_star = "ou=*"; 


//	$projecturl = get_project_url(); 						// "192.168.1.202"
//	$ldapconnectaddress = get_ldap_connect_address();		// "127.0.0.1"
//	$ldapconnectport = get_ldap_connect_port();				// 389
//	$adminbindrdn = get_admin_bind_rdn();					// cn=admin,dc=elenoon,dc=ir
//	$adminbindpass = get_admin_bind_pass();					// ahmad@91
//	$mainserverrdn = get_main_server_rdn();					// dc=elenoon,dc=ir
//	$customadmindn = get_custom_admin_dn();					// cn=customadmin
//	$customadminrdn = get_custom_admin_rdn();				// cn=customadmin,dc=elenoon,dc=ir
//	$customadminusername = get_custom_admin_username();		// customadmin
//	$customadminpass = get_custom_admin_pass();				// abas?1371
//	$bindadmindn = get_bind_admin_dn();						// cn=admin
//	$bindadminrdn = get_bind_admin_rdn();					// cn=admin,dc=elenoon,dc=ir
//	$indexpagelocation = get_index_page_location();			// /ldappanel/index.php
//	$panelpagelocation = get_panel_page_location();			// /ldappanel/panel.php 
//	$logoutpagelocation = get_logout_page_location();		// /ldappanel/logout.php
//	$node1rdn = get_node1_rdn();							// ou=node1,dc=elenoon,dc=ir
//	$node1usablerdn = get_node1_usable_rdn();				//,ou=node1,dc=elenoon,dc=ir
//	$zarafauserserver = get_zarafauserserver();				// 192.168.0.22
//	$oustar = get_ou_star(); 								// ou=*



	//-------------project get parameter functions--------------

	function get_project_url(){
		global $project_url;
		return $project_url;
	}
	
	function get_ldap_connect_address(){
		global $ldap_connect_address;
		return $ldap_connect_address;
	}
	
	function get_ldap_connect_port(){
		global $ldap_connect_port;
		return $ldap_connect_port;
	}
	
	function get_admin_bind_rdn(){
		global $admin_bind_rdn;
		return $admin_bind_rdn;
	}
	
	function get_admin_bind_pass(){
		global $admin_bind_pass;
		return $admin_bind_pass;
	}
	
	function get_main_server_rdn(){
		global $main_server_rdn;
		return $main_server_rdn;
	}
	
	function get_custom_admin_dn(){
		global $custom_admin_dn;
		return $custom_admin_dn;
	}

	function get_custom_admin_rdn(){
		global $custom_admin_rdn;
		return $custom_admin_rdn;
	}

	function get_bind_admin_dn(){
		global $custom_admin_dn;
		return $custom_admin_dn;
	}

	function get_bind_admin_rdn(){
		global $custom_admin_rdn;
		return $custom_admin_rdn;
	}
	
	function get_custom_admin_username(){
		global $custom_admin_username;
		return $custom_admin_username;
	}

	function get_custom_admin_pass(){
		global $custom_admin_pass;
		return $custom_admin_pass;
	}
	
	function get_index_page_location(){
		global $index_page_location;
		return $index_page_location;
	}

	function get_logout_page_location(){
		global $logout_page_location;
		return $logout_page_location;
	}

	function get_panel_page_location(){
		global $panel_page_location;
		return $panel_page_location;
	}
	
	function get_node1_rdn(){
		global $node1_rdn;
		return $node1_rdn;
	}
	
	function get_node1_usable_rdn(){
		global $node1_usable_rdn;
		return $node1_usable_rdn;
	}
	
	function get_zarafauserserver(){
		global $zarafauserserver;
		return $zarafauserserver;
	}
	function get_gid_number(){

	};
	function get_ou_star(){
		global $ou_star;
		return $ou_star;
	}
	function get_home_directory(){

	};
	 


?>
