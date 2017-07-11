<?php
namespace LP\LDAP\AUTH\CONNECT;

ini_set('display_errors',1); 
 error_reporting(E_ALL);
 
class ldapConnector extends ldapinfo{
	
  private $ldap_helper;

  	public static function ldapconnectandbind(){

  		$ldap_info = ldapinfo::get_ldap_info();
  		
  		$ds=ldap_connect( $ldap_info["ldapaddress"], $ldap_info["ldapport"]);

  		ldap_set_option(NULL, LDAP_OPT_DEBUG_LEVEL, 7);
        ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION,3);
		ldap_set_option($ds, LDAP_OPT_REFERRALS,0);
  		
  		if ($ds) {
		
		$r=ldap_bind($ds, $ldap_info["ldapbindrdn"], $ldap_info["ldapbindpass"]);

			if ($r) {

					return $ds;
					ldap_close($ds);
		
					}
	
				}   else {

				ldap_close($ds);
				return "hoboy";
				
		    	}

		}

}

?>