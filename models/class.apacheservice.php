<?php
namespace LP\LDAP\OBJECTS;

require_once 'lib/function.php';
require_once 'models/class.ldapinfo.php';
require_once 'models/class.ldapconnect.php';

use LP\LDAP\AUTH\CONNECT\ldapConnector as connect;
use LP\LDAP\AUTH\CONNECT\ldapinfo as ldapinfo;

ini_set('display_errors',1); 
 error_reporting(E_ALL);

class apacheservice{

 	private $apache_session_number = 0;


 	public function apacheconnect(){

 		$ldap_info = ldapinfo::get_ldap_info();

 		$apache_port = (int)$ldap_info['apachemachineport'];

 	//	var_dump($apache_port);

 		$methods = array('hostkey' => 'ssh-rsa');

 		$connection = ssh2_connect($ldap_info['apachemachineserver'], $apache_port ,$methods);

 		if(ssh2_auth_password($connection, $ldap_info['apachemachineuser'] , $ldap_info['apachemachinepassword'])){

		$stream = ssh2_exec($connection, 'cd /var/lib/php/session/ && ls | wc -l');

		stream_set_blocking( $stream, true );

		$stream_content = stream_get_contents($stream);

		$this->apache_session_number = $stream_content;

		//var_dump($stream_content);

		return true;

		}
		else{

			return false;
		}

 	}

 	public function getonlinesnumber(){

 		return $this->apache_session_number;

 	}

}

?>