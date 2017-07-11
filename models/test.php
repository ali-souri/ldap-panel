<?php

ini_set('display_errors',1); 
 error_reporting(E_ALL);

require_once 'class.ZCPservice.php';
// require '../lib/function.php';
echo "salam";

// use LP\LDAP\OBJECTS\zcpservice as zcpservice;

	


// header('Content-type: application/json');

// $zpc = new zcpconnect();

// $zcp->zcpconnect();

// $ds=ldap_connect( "localhost", "389" );
// ldap_set_option(NULL, LDAP_OPT_DEBUG_LEVEL, 7);
//                         ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION,3);
//                         ldap_set_option($ds, LDAP_OPT_REFERRALS,0);

// if ($ds) {

// 	$r=ldap_bind($ds, "cn=admin,dc=elenoon,dc=ir" , "abas");
// 	if ($r) {

// 		//$dn = "ou=pooriaTest,ou=node1,dc=elenoon,dc=ir";
// 	 $dn = "dc=elenoon,dc=ir";

// 	 $sr = ldap_search($ds, $dn, "ou=*");

// 	 $info = ldap_get_entries($ds , $sr);

// 	 var_dump($info);

	//  foreach ($info as $key => $value) {

	//  		if($key !== "count"){

	//  			$dname = $value['dn'];

	//  $result = get_parent_from_dn($dname);

	//  var_dump($ds, $result);
	// }
	
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
			// fclose($handle);

	

	// if($lr){

	// 		$info = ldap_get_entries($ds,$lr);
	// 		var_dump($info);

	
	// 	//$sr = ldap_list($ds, $dn, "uid=*");
	// $lr=ldap_list($ds, $dn , "uid=*");
	// if($lr){

	// 	$info = ldap_get_entries($ds, $lr);
	// 	// $flag = true;
	// 	foreach ($info as $key => $value) {

	// 		if($key !== "count"){

	// 			$dname = $value['dn'];
	// 			$flag = ldap_delete($ds, $dname);
				
	// 			if($flag==false)
	// 				return false;
	// 		}
	// 	}



// 	foreach ($info as $key => $value) {

// 		if($key!=="count"){

// 		$dn = $value["dn"];
		
// 		$sr = ldap_search($ds, $dn , "ou=*");
		
// 		if($sr){

// 		$info1 = ldap_get_entries($ds, $sr);

// 		foreach ($info1 as $key1 => $value1) {
// 			if($key1!=="count" && $value1["dn"]!==$dn){

// 				var_dump($value1["dn"]);
			
// 			}

// 			}

// 		}
// 	}
// }

	 // $id = 0;
	 // $json_last_array = Array();


	 // var_dump($info1);

	 // foreach ($info as $key => $value) {
	 // 	 if ($key!=="count") {
	 // 	 	$explode = explode("," , $info[$id]["dn"]);
	 // 	 	$last_explode = $explode[0] . "," .$explode[1];
	 // 		$helper_array = Array("mobile" => $info[$id]["mobile"][0] , "address" => $info[$id]["mail"][0] , "name" => $info[$id]["cn"][0] , "ou" => $last_explode);
 	//  			$json_last_array[$id+1] = $helper_array;
 	//  			$id++;
	 // 	 	}
	 // 	}
	 


	//echo json_encode($json_last_array);

	
			
// 		}
// ldap_close($ds);
// 	} else {
// 	echo "<h4>Unable to connect to LDAP server</h4>";
// }


	?>



