<?php
namespace LP\LDAP\AUTH\CONNECT;//\INFO;

ini_set('display_errors',1); 
 error_reporting(E_ALL);
require "models/class.config.php";
class ldapinfo {

	public static function get_ldap_info() {

		$xml = XML\ldaphelper::getLdapValues();
	    $deXml = simplexml_load_string($xml);
	    $deJson = json_encode($deXml);
	    $xml_array = json_decode($deJson,TRUE);
	    
	        return $xml_array;

	}

	public static function get_gid_values() {

		$xml = XML\ldaphelper::getgidvalues();
	    $deXml = simplexml_load_string($xml);
	    $deJson = json_encode($deXml);
	    $xml_array = json_decode($deJson,TRUE);
	    
	        return $xml_array;

	}

	public static function getLdapAttributesMap(){
		$ldap_attribute_array=array("cn"=>"نام کامل","mail"=>"آدرس ایمیل","userpassword"=>"کلمه عبور","sn"=>"شناسه","zarafaaccount"=>"فعال بودن اکانت","zarafaadmin"=>"مدیریت","zarafaalertsms"=>"پیامک هشدار ورود به سیستم","zarafaquotahard"=>"فضا به مگابایت","zarafaquotasoft"=>"هشدار اتمام حجم","zarafaquotawarn"=>"اخطار تمام شدن فضا","zarafasharedstoreonly"=>"قفل شدن اکانت");
		return $ldap_attribute_array;
	}

}
?>