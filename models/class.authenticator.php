<?php
namespace LP\LDAP\AUTH;
ini_set('display_errors',1); 
 error_reporting(E_ALL);
require_once 'models/class.ldapinfo.php';
require_once "models/class.ldapconnect.php";
require_once 'lib/function.php';

class authenticator {

	public static function authenticate($user,$pass){
		
		$ds=CONNECT\ldapConnector::ldapconnectandbind();

		$ldap_info =CONNECT\ldapinfo::get_ldap_info();

		if ($user=="customadmin") {
		
			 	$dn = "cn=".$user. "," .$ldap_info["mainserverrdn"];
		        $attr = "userpassword";
		        $r=ldap_compare($ds, $dn, $attr, $pass);

		          if ($r === -1) {
		            echo "Error: " . ldap_error($ds);
		        } elseif ($r === true) {
		        	$output_array = array("status"=>true,"dn"=>"customadmin");
		            return $output_array;
		        } elseif ($r === false) {
		        	$output_array = array("status"=>false);
		            return $output_array;
		        }

		    }else{

					$search_result = ldap_search($ds, $ldap_info["mainserverrdn"], "(|(cn=".$user.")(mobile=".$user."))");

					$entries_array = ldap_get_entries($ds,$search_result);

					if ($entries_array["count"]==0) {
		        		$output_array = array("status"=>false);
						return $output_array;
					}
					
					foreach ($entries_array as $key => $value) {
						if ($key!=="count") {
							if(password_check($value["userpassword"]["0"] , $pass , "userpassword")&&($value["zarafaadmin"]["0"]=="1")){
								$output_array = array("status"=>true,"dn"=>$value['dn']);
								return $output_array;
							}else continue;
						}
					}

			}
		        	$output_array = array("status"=>false);
				return $output_array;
		    }
	
	
	public static function authorize_in_ou($user_ou){
		if($user_ou == "customadmin"){
			return "ou=*";
		}
		else{
			$ldap_info =CONNECT\ldapinfo::get_ldap_info();

			$ds=CONNECT\ldapConnector::ldapconnectandbind();

			$user_ous = array();

			$search_result=ldap_search($ds, $ldap_info['mainserverrdn'] , $ldap_info['oustar']);

			$ous_array = ldap_get_entries($ds,$search_result);

			foreach ($ous_array as $key => $value){

				if (($key!=="count")&&(!is_ou_in_first_level($value['dn']))) {

					$descr_array = json_decode($value["description"]["0"],true);
					
					$managers_array = $descr_array['managers'];

					foreach ($managers_array as $key1 => $value1) {

						if ($key!=="count") {
							if ($value1["manager_dn"]==$user_ou) {
								if (!in_array(get_rdn_from_dn($value["dn"]), $user_ous)) {
									$user_ous[] = get_rdn_from_dn($value["dn"]);
								}
							}
						}
					}
				}
			}
			//var_dump($user_ous);
			return implode(",", $user_ous);
		}
	}
}