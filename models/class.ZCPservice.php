<?php
namespace LP\LDAP\OBJECTS;

require_once 'lib/function.php';
require_once 'models/class.ldapinfo.php';
require_once 'models/class.ldapconnect.php';

use LP\LDAP\AUTH\CONNECT\ldapConnector as connect;
use LP\LDAP\AUTH\CONNECT\ldapinfo as ldapinfo;
//use \ssh2_connect as ssh2_connect;

ini_set('display_errors',1); 
 error_reporting(E_ALL);

 class zcpservice {


 	private $users_capacity_array = array();

 	private $user_index = 0;

 	public function zcpconnect(){

 		$ldap_info = ldapinfo::get_ldap_info();

 		$zcp_port = (int)$ldap_info['zcpmachineport'];

 		//var_dump($zcp_port);

 		$methods = array('hostkey' => 'ssh-rsa');

 		$connection = ssh2_connect($ldap_info['zcpmachineserver'], $zcp_port ,$methods);

 		if(ssh2_auth_password($connection, $ldap_info['zcpmachineuser'] , $ldap_info['zcpmachinepassword'])){

		$stream = ssh2_exec($connection, 'cd /tmp && sh qu.sh');

		stream_set_blocking( $stream, true ); 

		$stream_content = stream_get_contents($stream);

		$stream_content_array = preg_split("/[\s]+/", $stream_content);

		$temp_users_array = array();
		$temp_index = 0;

		foreach ($stream_content_array as $key => $value) {

			if ($value == "Hard") {

				$current_user_array = array();

				$current_user_array["cap"] = $stream_content_array[$key+2];
				$current_user_array['used'] = $stream_content_array[$key+7];

				$temp_users_array[$temp_index] = $current_user_array;

				$temp_index++;

			}
			
		}
		// var_dump($temp_users_array);

		$this->users_capacity_array = $temp_users_array;

		fclose($stream);

		return true;

		}
		else{

			return false;
		}

 	}

 	public function zcpgetusersarray(){

 		return $this->users_capacity_array;

 	}
 }

 ?>