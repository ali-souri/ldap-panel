<?php

require_once 'models/class.ldapinfo.php';
require 'models/class.ldapconnect.php';
require 'models/class.ou.php';

ini_set('display_errors',1); 
 error_reporting(E_ALL);

use LP\PAGE\OBJECTS\OU as ou;

$ldap_info = LP\LDAP\AUTH\CONNECT\ldapinfo::get_ldap_info();
$gid_values = LP\LDAP\AUTH\CONNECT\ldapinfo::get_gid_values();
$ldap_attributes_list = LP\LDAP\AUTH\CONNECT\ldapinfo::getLdapAttributesMap();

function get_search_variable($our_element){
		if($our_element=="address"){
			return "mail";
		}elseif ($our_element=="mobile") {
			return "mobile";
		};
	}

function sshaEncode($text){
		$salt = '';
			for ($i=1; $i<=10; $i++) {
				$salt .= substr('0123456789abcdef', rand(0, 15), 1);
			}
		$hash = '{SSHA}' . base64_encode(pack('H*',sha1($text . $salt)) . $salt);
		return $hash;
	}

function sshaCustom($text){
	$hash = "{SSHA}" . base64_encode(sha1($text));
	return $hash;
}

function create_user_array($name,$mobile,$email,$pass,$externaloufromui){

		global $ldap_info;
		global $gid_values;

		$zarafauserserver = $ldap_info["zarafauserserver"];

		$node1usablerdn = $ldap_info["node1usablerdn"];

		$atdomain = "@" . $ldap_info["ldapdomain"];

		$gidbundle = substr($externaloufromui, 3);

		if (($externaloufromui=="ou=khorasan jonobi")||($externaloufromui=="ou=khorasan razavi")||($externaloufromui=="ou=khorasan shomali")) {
		
			$gidbundle = str_replace(" ", "_", substr($externaloufromui, 3));

		}

		$gidnumber = $gid_values[$gidbundle];

		$return_array = Array();

		$dn = "uid=" . $mobile . $atdomain . "," . $externaloufromui . $node1usablerdn;

		$externalou = "uid=" . $mobile . $atdomain . "," . $externaloufromui ;

		$homedirectory = "/home/98" . $mobile . $gidnumber;

		$entry_array = Array ( "cn" => $name  , "gidnumber" =>  $gidnumber , "homedirectory" => $homedirectory , "mail" => $email , "mobile" => $mobile ,"objectclass" => Array ("0" => "posixAccount" , "1" => "top" , "2" => "zarafa-user" , "3" => "inetOrgPerson")  , "sn" => $mobile  , "uid"  => $email  , "uidnumber" => $gidnumber , "userpassword" => sshaEncode($pass) , "zarafaaccount" =>  1 , "zarafaadmin" =>  0 , "zarafaalertsms" =>  1 , "zarafaaliases" =>  $mobile.$atdomain , "zarafaconfirm" =>  1 , "zarafaenabledfeatures" => Array (  "0" => "imap" , "1" => "pop3" ) , "zarafahidden" =>  1 , "zarafaquotahard" =>  1900 , "zarafaquotaoverride" => 1 , "zarafaquotasoft" => 1800 , "zarafaquotawarn" =>  1700 , "zarafasharedstoreonly" => 0 ,  "zarafauserserver" =>  $zarafauserserver) ;

		$return_array[0] = $dn;
		$return_array[1] = $entry_array;

		return $return_array;

}

function get_add_user_array($parentourdn,$name,$mobile,$email,$pass){

		global $ldap_info;
		global $gid_values;

		$zarafauserserver = $ldap_info["zarafauserserver"];

		$node1usablerdn = $ldap_info["node1usablerdn"];

		$atdomain = "@" . $ldap_info["ldapdomain"];

		$gidnumber = get_ou_gidnumber(explode("," , $parentourdn)[0]);

		$dn = "uid=" . $mobile . $atdomain . "," . $parentourdn;

		$explode_item = explode(",", $dn);

		$externalou = "uid=" . $mobile . $atdomain . "," . $explode_item[0] . "," . $explode_item[1] ;

		$homedirectory = "/home/98" . $mobile . $gidnumber;

		$uidnumber = "98" . $mobile . $gidnumber;

		$explode = explode("@", $email);
		$sn = $explode[0];

		$respons_success_array = Array("0"=>Array("status"=>"true","ou"=>$externalou));
		$respons_error_array = Array("0"=>Array("status"=>"fulse"));

		$entry_array = Array ( "cn" => $name  , "gidnumber" =>  $gidnumber , "homedirectory" => $homedirectory , "mail" => $email , "mobile" => $mobile ,"objectclass" => Array ("0" => "posixAccount" , "1" => "top" , "2" => "zarafa-user" , "3" => "inetOrgPerson")  , "sn" => $mobile  , "uid"  => $email  , "uidnumber" => $gidnumber , "userpassword" => sshaEncode($pass) , "zarafaaccount" =>  1 , "zarafaadmin" =>  0 , "zarafaalertsms" =>  1 , "zarafaaliases" =>  $mobile.$atdomain , "zarafaconfirm" =>  1 , "zarafaenabledfeatures" => Array (  "0" => "imap" , "1" => "pop3" ) , "zarafahidden" =>  1 , "zarafaquotahard" =>  1900 , "zarafaquotaoverride" => 1 , "zarafaquotasoft" => 1800 , "zarafaquotawarn" =>  1700 , "zarafasharedstoreonly" => 0 ,  "zarafauserserver" =>  $zarafauserserver) ;

		return Array("entry_array"=>$entry_array,"success_array"=>$respons_success_array,"error_array"=>$respons_error_array,"dn"=>$dn);

}

function get_ou_gidnumber($ou_rdn){

	$ds=LP\LDAP\AUTH\CONNECT\ldapConnector::ldapconnectandbind();

	$sr=ldap_search($ds, "dc=elenoon,dc=ir" , $ou_rdn);
		
		$info = ldap_get_entries($ds , $sr);

		$gid_number = json_decode($info[0]['description'][0],true)['gid_number'];

		ldap_close($ds);

		return $gid_number;

}

function user_add($ldap_dn,$ldap_entry_array){

	global $ldap_info;

	$ds=LP\LDAP\AUTH\CONNECT\ldapConnector::ldapconnectandbind();
	
		$sr=ldap_add($ds , $ldap_dn , $ldap_entry_array);
		if ($sr) {

			  return true;

		 		  }else{return false;};				 	

}

function ou_modify($ou_array){

	global $ldap_info;

	$modify_value =  array("description"=>json_encode($ou_array['descr']));

	$ds=LP\LDAP\AUTH\CONNECT\ldapConnector::ldapconnectandbind();
	
		$sr=ldap_modify($ds , $ou_array['dn'] , $modify_value);
		if ($sr) {

			  return true;

		 		  }else{return false;};	
}

function get_response_success($input_respons_success_array){
	$respons_success_array = Array("0"=>Array("responsestatustext"=>"با تشکر. تعداد کاربر به این استان اضافه شدند.","updatetableinfo"=>$input_respons_success_array));
	return $respons_success_array;

}

function get_name_from_dn($insert_dn){
	$explode1 = explode("," , $insert_dn);
	 return substr($explode1[0], 3);
}

function get_cn_by_dn($dn){

	global $ldap_info;
	
	$ds=LP\LDAP\AUTH\CONNECT\ldapConnector::ldapconnectandbind();

	$sr=ldap_search($ds, $ldap_info['mainserverrdn'] , get_rdn_from_dn($dn));

	$info = ldap_get_entries($ds, $sr);

	return $info[0]['cn'][0];

}

function get_parent_from_dn($inserted_dn){
	$explode2 = explode("," , $inserted_dn);
	return $explode2[1];
}

function get_parent_id($insert_string,$json_last_array){
	$explode = explode("," , $insert_string);
	foreach ($json_last_array as $key => $val) {
       if ($val["text"] === $explode[1]) {
           return $val["id"];
       }
   };
   return "#";
}

function get_rdn_from_dn($insert_dn){
	$explode = explode("," , $insert_dn);
	return $explode[0];
}

function rdn_to_dn($rdn){

	global $ldap_info;

	$ds=LP\LDAP\AUTH\CONNECT\ldapConnector::ldapconnectandbind();

	$sr=ldap_search($ds, $ldap_info['mainserverrdn'] , $rdn);

	$entry = ldap_get_entries($ds, $sr);

	return $entry["0"]["dn"];

}

function password_check($cryptedpassword,$plainpassword,$attribute='userpassword') {

	if (preg_match('/{([^}]+)}(.*)/',$cryptedpassword,$matches)) {
		$cryptedpassword = $matches[2];
		$cypher = strtolower($matches[1]);

	} else {
		$cypher = null;
	}

	switch($cypher) {
		# SSHA crypted passwords
		case 'ssha':
			# Check php mhash support before using it
			if (function_exists('mhash')) {
				$hash = base64_decode($cryptedpassword);

				# OpenLDAP uses a 4 byte salt, SunDS uses an 8 byte salt - both from char 20.
				$salt = substr($hash,20);
				$new_hash = base64_encode(mhash(MHASH_SHA1,$plainpassword.$salt).$salt);

				if (strcmp($cryptedpassword,$new_hash) == 0)
					return true;
				else
					return false;

			} else {
				error(_('Your PHP install does not have the mhash() function. Cannot do SHA hashes.'),'error','index.php');
			}

			break;

		# Salted MD5
		case 'smd5':
			# Check php mhash support before using it
			if (function_exists('mhash')) {
				$hash = base64_decode($cryptedpassword);
				$salt = substr($hash,16);
				$new_hash = base64_encode(mhash(MHASH_MD5,$plainpassword.$salt).$salt);

				if (strcmp($cryptedpassword,$new_hash) == 0)
					return true;
				else
					return false;

			} else {
				error(_('Your PHP install does not have the mhash() function. Cannot do SHA hashes.'),'error','index.php');
			}

			break;

		# SHA crypted passwords
		case 'sha':
			if (strcasecmp(password_hash($plainpassword,'sha'),'{SHA}'.$cryptedpassword) == 0)
				return true;
			else
				return false;

			break;

		# MD5 crypted passwords
		case 'md5':
			if( strcasecmp(password_hash($plainpassword,'md5'),'{MD5}'.$cryptedpassword) == 0)
				return true;
			else
				return false;

			break;

		# Crypt passwords
		case 'crypt':
			# Check if it's blowfish crypt
			if (preg_match('/^\\$2+/',$cryptedpassword)) {

				# Make sure that web server supports blowfish crypt
				if (! defined('CRYPT_BLOWFISH') || CRYPT_BLOWFISH == 0)
					error(_('Your system crypt library does not support blowfish encryption.'),'error','index.php');

				list($version,$rounds,$salt_hash) = explode('$',$cryptedpassword);

				if (crypt($plainpassword,'$'.$version.'$'.$rounds.'$'.$salt_hash) == $cryptedpassword)
					return true;
				else
					return false;
			}

			# Check if it's an crypted md5
			elseif (strstr($cryptedpassword,'$1$')) {

				# Make sure that web server supports md5 crypt
				if (! defined('CRYPT_MD5') || CRYPT_MD5 == 0)
					error(_('Your system crypt library does not support md5crypt encryption.'),'error','index.php');

				list($dummy,$type,$salt,$hash) = explode('$',$cryptedpassword);

				if (crypt($plainpassword,'$1$'.$salt) == $cryptedpassword)
					return true;
				else
					return false;
			}

			# Check if it's extended des crypt
			elseif (strstr($cryptedpassword,'_')) {

				# Make sure that web server supports ext_des
				if (! defined('CRYPT_EXT_DES') || CRYPT_EXT_DES == 0)
					error(_('Your system crypt library does not support extended DES encryption.'),'error','index.php');

				if (crypt($plainpassword,$cryptedpassword) == $cryptedpassword)
					return true;
				else
					return false;
			}

			# Password is plain crypt
			else {

				if (crypt($plainpassword,$cryptedpassword) == $cryptedpassword)
					return true;
				else
					return false;
			}

			break;

		# SHA512 crypted passwords
		case 'sha512':
			if (strcasecmp(password_hash($plainpassword,'sha512'),'{SHA512}'.$cryptedpassword) == 0)
				return true;
			else
				return false;

			break;

		# No crypt is given assume plaintext passwords are used
		default:
			if ($plainpassword == $cryptedpassword)
				return true;
			else
				return false;
	}
}

function delete_uids_of_dn($dn){

	$ds=LP\LDAP\AUTH\CONNECT\ldapConnector::ldapconnectandbind();

	$sr = ldap_search($ds, $dn, "uid=*");

	if($sr){

		$info = ldap_get_entries($ds, $sr);

		foreach ($info as $key => $value) {

			if($key !== "count"){

				$dname = $value['dn'];
				$flag = ldap_delete($ds, $dname);

				if(!$flag){
					ldap_close($ds);
					return false;
				}

			}
		}

		ldap_close($ds);
		return true;

	}else{

		ldap_close($ds);
		return false;
	}

}

function check_dn_in_ldap_array($dn,$ldap_array){
	foreach ($ldap_array as $key => $value) {
		if ($value['dn']==$dn) {
			return true;
		}
	}
	return false;
}

function getLdapAttributesList(){
	global $ldap_attributes_list;
	$ldap_allowed_attributes_array = array();
	foreach ($ldap_attributes_list as $key => $value) {
		$ldap_allowed_attributes_array[] = $key;
	}
	return $ldap_allowed_attributes_array;
}

function managers_dn_list_for_ou_class($descr_array){
	$name_id=0;
	$names = array();
	foreach ($descr_array['managers'] as $key => $value) {
			$names[$name_id] = $value->manager_dn;
			$name_id++;
	}
	return $names;
}

function manager_array_for_ou_class($descr_array){
	$name_id=0;
	$names = array();
	$helper_array = array();
	foreach ($descr_array as $key => $value) {
			$helper_array["manager_dn"] = $value->manager_dn;
			$helper_array["manager_permissions"] = (array) $value->manager_permissions;
			$names[$name_id] = $helper_array;
			$name_id++;
	}
	return $names;
}

function get_ou_object_from_dn($ou_dn){

	global $ldap_info;

	$ds=LP\LDAP\AUTH\CONNECT\ldapConnector::ldapconnectandbind();

	$sr = ldap_search($ds, $ldap_info['mainserverrdn'] , get_rdn_from_dn($ou_dn));

	if($sr){

		$info = ldap_get_entries($ds, $sr);

		$ou_object_array = array("dn"=>$info[0]['dn'],"descr"=>json_decode($info[0]['description'][0]));

		return new ou($ou_object_array);
	
	}else{
		return 0;
	}
}

function get_ou_object_from_ou_array($ou_array){

	if($ou_array){

		$ou_object_array = array("dn"=>$ou_array['dn'],"descr"=>json_decode($ou_array['description'][0]));

		return new ou($ou_object_array);
	
	}else{
		return 0;
	}
}

function is_ou_in_first_level($ou_dn){

	global $ldap_info;

			$ds=LP\LDAP\AUTH\CONNECT\ldapConnector::ldapconnectandbind();

			$search_result=ldap_list($ds, $ldap_info['mainserverrdn'] , $ldap_info['oustar']);

			$ous_arrays = ldap_get_entries($ds, $search_result);

			foreach ($ous_arrays as $key => $value) {
				if ($value['dn']==$ou_dn) {
					return true;
				}
			}
			return false;
}

function array_column($input = null, $columnKey = null, $indexKey = null){
        // Using func_get_args() in order to check for proper number of
        // parameters and trigger errors exactly as the built-in array_column()
        // does in PHP 5.5.
        $argc = func_num_args();
        $params = func_get_args();

        if ($argc < 2) {
            trigger_error("array_column() expects at least 2 parameters, {$argc} given", E_USER_WARNING);
            return null;
        }

        if (!is_array($params[0])) {
            trigger_error('array_column() expects parameter 1 to be array, ' . gettype($params[0]) . ' given', E_USER_WARNING);
            return null;
        }

        if (!is_int($params[1])
            && !is_float($params[1])
            && !is_string($params[1])
            && $params[1] !== null
            && !(is_object($params[1]) && method_exists($params[1], '__toString'))
        ) {
            trigger_error('array_column(): The column key should be either a string or an integer', E_USER_WARNING);
            return false;
        }

        if (isset($params[2])
            && !is_int($params[2])
            && !is_float($params[2])
            && !is_string($params[2])
            && !(is_object($params[2]) && method_exists($params[2], '__toString'))
        ) {
            trigger_error('array_column(): The index key should be either a string or an integer', E_USER_WARNING);
            return false;
        }

        $paramsInput = $params[0];
        $paramsColumnKey = ($params[1] !== null) ? (string) $params[1] : null;

        $paramsIndexKey = null;
        if (isset($params[2])) {
            if (is_float($params[2]) || is_int($params[2])) {
                $paramsIndexKey = (int) $params[2];
            } else {
                $paramsIndexKey = (string) $params[2];
            }
        }

        $resultArray = array();

        foreach ($paramsInput as $row) {

            $key = $value = null;
            $keySet = $valueSet = false;

            if ($paramsIndexKey !== null && array_key_exists($paramsIndexKey, $row)) {
                $keySet = true;
                $key = (string) $row[$paramsIndexKey];
            }

            if ($paramsColumnKey === null) {
                $valueSet = true;
                $value = $row;
            } elseif (is_array($row) && array_key_exists($paramsColumnKey, $row)) {
                $valueSet = true;
                $value = $row[$paramsColumnKey];
            }

            if ($valueSet) {
                if ($keySet) {
                    $resultArray[$key] = $value;
                } else {
                    $resultArray[] = $value;
                }
            }

        }

        return $resultArray;
    }

function set_zarafa_admin($admin_dn){

	$modify_array = array("zarafaadmin"=>array(0=>1));

	$ds=LP\LDAP\AUTH\CONNECT\ldapConnector::ldapconnectandbind();

	return ldap_modify($ds, $admin_dn, $modify_array);

}

function check_ou_maneger($user_dn,$ou_array){

	$ou_descr_array = json_decode($ou_array['description'][0],true);

	$ou_manegers_array = $ou_descr_array['managers'];

	foreach ($ou_manegers_array as $key => $value) {
		if ($value['manager_dn']==$user_dn) {
			return true;
		}
	}
	return false;
}

function userpassword_to_six_star($value){
	if ($value=="userpassword") {
		return "******";
	}else{return $value;}
}
?>