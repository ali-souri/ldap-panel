<?php

	//$ou = $_REQUEST['ou'];
ini_set('display_errors',1); 
 error_reporting(E_ALL);

//  require_once 'models/class.ZCPservice.php';
// use LP\LDAP\OBJECTS\zcpservice as zcpservice;

require_once 'models/class.apacheservice.php';
use LP\LDAP\OBJECTS\apacheservice as apacheservice;

 
echo "salam";

$zcp = new apacheservice();

$zcp->apacheconnect();

// header('Content-type: application/json');


	//$search_ou = "ou=*";

// $ds=ldap_connect( "localhost", "389" );
// ldap_set_option(NULL, LDAP_OPT_DEBUG_LEVEL, 7);
//                         ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION,3);
//                         ldap_set_option($ds, LDAP_OPT_REFERRALS,0);

//                         $myarray=array("name"=>"ali","family"=>"souri");
//                         var_dump(in_array("ali",$myarray));

// if ($ds) {

// 	$r=ldap_bind($ds, "cn=admin,dc=elenoon,dc=ir" , "abas");
// 	if ($r) {

	//	$dn = "ou=pooriaTest,ou=node1,dc=elenoon,dc=ir";
	 
	
	// $test_array = Array("objectclass" => Array ("0" => "organizationalUnit" , "1" => "top" ), "ou" => "pooriaTest");
	// $result = Array("test_array"=>$test_array,"dn"=>$dn);

	// $sr=ldap_add($ds , $result["dn"] , $result["test_array"]);

			// $my_file = 'gidnumber.txt';
			// $handle = fopen($my_file, 'a+') or die('Cannot open file:  '.$my_file);
			// $data = fread($handle,filesize($my_file));
			// $data = (int) $data;
			//  //echo $data;
			// var_dump($data);

			// $data++;
			// //$new =fwrite($handle, $data1); 
			
			// file_put_contents($my_file, $data);
			// var_dump($data);
			// //echo $new;
			// //var_dump($new);
			// fclose($handle);//(|(cn=".$user.")(mobile=".$user."))

	//$lr=ldap_list($ds, "dc=elenoon,dc=ir" , "ou=*");

// 	$lr=ldap_search($ds, "dc=elenoon,dc=ir" , "uid=*");

// if($lr){

// 		$info = ldap_get_entries($ds, $lr);
// 		//var_dump($info[0]['description'][0]);
// //	var_dump(json_decode($info[0]['description'][0],true)['gid_number']);
// 	var_dump($info);
	

// }
//$first_level=ldap_list($ds, $ldap_info["mainserverrdn"] , "ou=*");

					// if ($lr) {
			
					// 	$first_level_array = ldap_get_entries($ds, $lr);

					// 	$id = 0;
					// 	$ou_array = Array();

					// 	foreach ($first_level_array as $key => $value) {

					// 		if($key!=="count"){

					// 		$dn = $value["dn"];
				
					// 		$sr = ldap_search($ds, $dn , "ou=*");
				
					// 		if($sr){

					// 		$info1 = ldap_get_entries($ds, $sr);
							
					// //		var_dump($info1);
				
					// 			foreach ($info1 as $key1 => $value1) {
								
					// 				if($key1!=="count"){
			
					// 					$ou_array[$key1]=$value1;			

					// 				} 

					// 			}

					// 			var_dump($ou_array);
					// 		}
			
					// 		}
					// 	}
					// }

// }
// ldap_close($ds);
// } else {
// 	echo "<h4>Unable to connect to LDAP server</h4>";
// }


	?>



