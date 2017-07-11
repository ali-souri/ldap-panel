<?php
 namespace LP;

 require "providers/class.lp.content.php";
 require "models/class.authenticator.php";
 require_once "models/class.ldapinfo.php";

 ini_set('display_errors',1); 
 error_reporting(E_ALL);


class lp {

private $user_viewed = "userviewed";

  public static function handleRequest(){

  	$user_authorized = "user_authorized";

  	session_start();

  	if (isset($_SESSION['user_authorized'])) {
  		
		if ($_SESSION['user_authorized']==0) {

			if ($_POST) {
				
				$user = $_POST['username'];
				$pass = $_POST['password'];

				$authentication_answer = LDAP\AUTH\authenticator::authenticate($user,$pass);

				if ($authentication_answer["status"]) {
				
					$_SESSION['user_authorized']=1;
					$_SESSION['user_ou'] = LDAP\AUTH\authenticator::authorize_in_ou($authentication_answer['dn']);
					$_SESSION['user_dn'] = $authentication_answer['dn'];
					$_SESSION['user_name'] = $user;

					echo '<META HTTP-EQUIV=Refresh CONTENT="0.01">';

				}else{
					
					echo "login failed";
				
				}
			} else 
				{
					$_SESSION['user_authorized']=5;
					echo '<META HTTP-EQUIV=Refresh CONTENT="0.01">';
				}
		} elseif ($_SESSION['user_authorized']==1) {

				if ($_GET) {
					
						foreach($_GET as $key => $value){						

							if ($key=="action") {

								if ($value=="xls") {

									$xls_content = array();
									$xls_content["myou"] = $_POST['myou'];
									
									if(isset($_FILES["myfile"])) {

										$xls_content["myfile"] = $_FILES["myfile"];

										PAGE\content::handlejsonrequest($value,$xls_content,$_SESSION['user_ou'],null,null);		
									
									}
								
								}
								
								else 
									{
										
										PAGE\content::handlejsonrequest($value,null,$_SESSION['user_ou'],null,null);
								
										}
							
							}

							elseif ($key=="page") {
								
								PAGE\content::handlejsonrequest($value,null,$_SESSION['user_ou'],null,null);

							}
							
							elseif ($key=="logout") {
								session_destroy();
								echo '<META HTTP-EQUIV=Refresh CONTENT="0.01">';
							}

						}

					}
					elseif ($_POST) {
						
   						 if((isset($_POST["username"]))||(isset($_POST["password"]))){
       
       					         unset($_POST["username"]);
								 unset($_POST["password"]);
			
							}

					$id=0;
					$action = "";
					$content = array();

					foreach($_POST as $key => $value){

					    if ($key=="action"){
							$action = $value;        
					    }else{
					    	$content[$key]=$value;
					    }
					
					}

					PAGE\content::handlejsonrequest($action,$content,$_SESSION['user_ou'],$_SESSION['user_name'],$_SESSION['user_dn']);

				}else{				

				PAGE\content::echopanelpage();
				
				}
		}
		elseif ($_SESSION['user_authorized']==5) {

			$_SESSION['user_authorized']=0;
			PAGE\content::echologin();
		
		}
	}
	else{
	
		$_SESSION['user_authorized']=0;
		PAGE\content::echologin();
	
	}

	}
}
?>
