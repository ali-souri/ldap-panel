<?php

namespace LP\PAGE\JSON;

require 'lib/function.php';
require_once 'models/class.ldapinfo.php';
require_once 'models/class.ldapconnect.php';
require_once 'models/class.ou.php';
require_once 'models/class.ZCPservice.php';
require_once 'models/class.apacheservice.php';
require 'lib/PHPExcel/IOFactory.php';

use LP\LDAP\AUTH\CONNECT\ldapConnector as connect;
use LP\LDAP\AUTH\CONNECT\ldapinfo as ldapinfo;
use \PHPExcel_IOFactory as PHPExcel_IOFactory;
use LP\LDAP\OBJECTS\apacheservice as apacheservice;
use LP\LDAP\OBJECTS\ZCPservice as zcpservice;

ini_set('display_errors',1); 
 error_reporting(E_ALL);

	class ldapaction {

		public static function nodecontent($request){

			header('Content-type: application/json');

			$ds=connect::ldapconnectandbind();

			$ldap_info = ldapinfo::get_ldap_info();
			
			$search_ou = rdn_to_dn($request["ou"]);

			$sr=ldap_search($ds, $search_ou , "uid=*");

				if ($sr) {
				
					$info = ldap_get_entries($ds, $sr);

					 $id = 0;

					 $json_last_array = Array();

					 foreach ($info as $key => $value) {
					 	 if ($key!=="count") {
					 	 	$explode = explode("," , $info[$id]["dn"]);
					 	 	$last_explode = $explode[0] . "," . $explode[1] . "," . $explode[2];
					 		$helper_array = Array("mobile" => $info[$id]["mobile"][0] , "address" => $info[$id]["mail"][0] , "name" => $info[$id]["cn"][0] , "ou" => $last_explode);
				 	 			$json_last_array[$id+1] = $helper_array;
				 	 			$id++;
					 	 	}
					 	}

					echo json_encode($json_last_array);

					ldap_close($ds);
				}

		}
		public static function oulist($ou){

			header('Content-type: application/json');
			
			if($ou=="ou=*"){

			$ds = connect::ldapconnectandbind();

			$ldap_info = ldapinfo::get_ldap_info();

			$first_level=ldap_list($ds, $ldap_info["mainserverrdn"] , "ou=*");

			if ($first_level) {
			
			$first_level_array = ldap_get_entries($ds, $first_level);

			$id = 0;
			$ou_array = Array();

			foreach ($first_level_array as $key => $value) {

				if($key!=="count"){

				$dn = $value["dn"];
				
				$sr = ldap_search($ds, $dn , "ou=*");
				
				if($sr){

				$info = ldap_get_entries($ds, $sr);

				foreach ($info as $key1 => $value1) {
					
					if($key1!=="count" && $value1["dn"]!==$dn){

						$ou = get_ou_object_from_dn($value1['dn']);

						$ou_array[$id] = Array("ou" => $value1["dn"],"name"=>get_name_from_dn($value1["dn"]),"capacity"=>$ou->get_max());
						$id++;
									}

								}

							}
						}
					}
				$response_array = Array("status"=>"true","array"=>$ou_array);
				echo json_encode($response_array);	
				}
			}

			else{

			$respons_error_array = Array("0"=>Array("status"=>"false"));
			echo json_encode($respons_error_array);

			}

		}

		public static function ouadd($request,$ou){

			header('Content-type: application/json');

			$ldap_info = ldapinfo::get_ldap_info();

			$respons_success_array = Array("status"=>"true");
			$respons_error_array = Array("status"=>"false");

			if($ou=="ou=*"){

			$ds=connect::ldapconnectandbind();

			$ou_name = $request['ouname'];

			$dn = "ou=".$ou_name.",ou=node1,dc=elenoon,dc=ir";

			$my_file = 'gidnumber.txt';
			$handle = fopen($my_file, 'a+') or die('Cannot open file:  '.$my_file);
			$data = fread($handle,filesize($my_file));

			$data = (int) $data;

			$data++; 

			file_put_contents($my_file, $data);

			fclose($handle);

			$desc_array = array("gid_number"=>$data,"max"=>$ldap_info['defaultoucapacity'],"managers"=>array("0"=>array("manager_dn"=>"uid=09127975061@fci.co.ir,ou=support,ou=node1,dc=elenoon,dc=ir","manager_permissions"=>array_keys(ldapinfo::getLdapAttributesMap()))));

			$ou_array = Array("description"=> json_encode($desc_array) ,"objectclass" => Array ("0" => "organizationalUnit" , "1" => "top", "2" =>"zarafa-company" ), "ou" => $ou_name);

			$sr=ldap_add($ds , $dn , $ou_array);

				if ($sr) {

				echo json_encode($respons_success_array);

				}else{

					echo json_encode($respons_error_array);

				}

			ldap_close($ds);

			}else{

					echo json_encode($respons_error_array);

				}
		
	}


		public static function oudelete($request,$ou){

			header('Content-type: application/json');

			$respons_success_array = Array("0"=>Array("status"=>"true"));
			$respons_error_array = Array("0"=>Array("status"=>"false"));

			if($ou=="ou=*"){

				$ds=connect::ldapconnectandbind();

				$dn = $request['dn'];

				if(delete_uids_of_dn($dn)){

					if(ldap_delete($ds, $dn)){

						echo json_encode($respons_success_array);

					}

				}else{

					echo json_encode($respons_error_array);

				}

				ldap_close($ds);

			}else{
				
				echo json_encode($respons_error_array);

			}

		}


		public static function useradd($request){

			header('Content-type: application/json');

			$ds=connect::ldapconnectandbind();

			$parentou = $request['parentou'];
			$name = $request['name'];
			$mobile = $request['mobile'];
			$email = $request['email'];
			$pass = $request['pass'];

			$parent_dn = rdn_to_dn($parentou);

			$entry_array_stack = get_add_user_array($parent_dn,$name,$mobile,$email,$pass);

			$sr=ldap_add($ds , $entry_array_stack["dn"] , $entry_array_stack["entry_array"]);

				if ($sr) {

					echo json_encode($entry_array_stack["success_array"]);
						
					}else{

						echo json_encode($entry_array_stack["error_array"]);
					
					}
			ldap_close($ds);

		}

		public static function userdelete($request){

			header('Content-type: application/json');

			$ds=connect::ldapconnectandbind();

			$ldap_info = ldapinfo::get_ldap_info();

			$ou = $request['ou'];

			$explode_ou = explode(",", $ou);

			if ($explode_ou[0]==$explode_ou[1]) {
			
			$dn = rdn_to_dn($explode_ou[0]);

			}else{

				$dn = $ou . "," . $ldap_info["mainserverrdn"];

			}

			$respons_success_array = Array("0"=>Array("status"=>"true"));
			$respons_error_array = Array("0"=>Array("status"=>"false"));

			$sr=ldap_delete($ds, $dn);

				if ($sr) {
						
					echo json_encode($respons_success_array);

					}else{

						echo json_encode($respons_error_array);
					
					}

			ldap_close($ds);

		}

		public static function usermodify($request){

			header('Content-type: application/json');

			$ds=connect::ldapconnectandbind();

			$ldap_info = ldapinfo::get_ldap_info();

			$ou = $request['ou'];
			$new_data = $request['new_data'];

			$usabale_new_data = json_decode($new_data,true);

			$entry_array = array();
			$id=0;

			foreach ($usabale_new_data as $key => $value) {
				
				if ($value['name']=="userpassword"){

						if($value['value']=='******') {
						continue;
						}else{
							$entry_array[$value['name']] = array(0=>sshaEncode($value['value']));
							$id++;
							continue;
						}

				}

				$entry_array[$value['name']] = array(0=>$value['value']);
				$id++;
			}

			$dn = $ou . "," . $ldap_info["mainserverrdn"];

			$respons_success_array = Array("0"=>Array("status"=>"true"));
			$respons_error_array = Array("0"=>Array("status"=>"false"));

			$sr=ldap_modify($ds , $dn , $entry_array);
				
				if ($sr) {

				echo json_encode($respons_success_array);

				}else{

					echo json_encode($respons_error_array);

				}

			ldap_close($ds);

		}

		public static function checkinputunique($request){

			header('Content-type: application/json');

			$ds=connect::ldapconnectandbind();

			$ldap_info = ldapinfo::get_ldap_info();

			$element = $request['el'];
			$element_type = $request['eltype'];

			$respons_success_array = Array("0"=>Array("status"=>"true"));
			$respons_error_array = Array("0"=>Array("status"=>"false"));

			$sr=ldap_search($ds, $ldap_info["node1rdn"] , get_search_variable($element_type)."=".$element);
								
			if ($sr) {
		
				$info = ldap_get_entries($ds, $sr);

					if ($info["count"]) {

						echo json_encode($respons_error_array);

					}else{

						echo json_encode($respons_success_array);	

					};

 			};

			ldap_close($ds);

		}

		public static function getmodifyattributes($admin_ou_list,$admin_dn,$request){

			header('Content-type: application/json');

			$ldap_info = ldapinfo::get_ldap_info();

			$ds=connect::ldapconnectandbind();

			$admin_ou_array = explode(",", $admin_ou_list); 

			$target_ou = $request['target_ou'];

			$explode_ou = explode("," , $target_ou);

			$uid = $explode_ou[0];

			if ($explode_ou[0]==$explode_ou[1]) {
				
			$parent_ou = $explode_ou[2];
			
			$grand_parent_ou = get_parent_from_dn(rdn_to_dn($explode_ou[2]));

			}else{

			$parent_ou = $explode_ou[1];
			
			$grand_parent_ou = $explode_ou[2];

			}

			$user_uid_dn = $target_ou . "," . $ldap_info['mainserverrdn'];

			

			$target_ou_dn = $parent_ou . "," . $grand_parent_ou . "," . $ldap_info['mainserverrdn'];

			$ou = get_ou_object_from_dn($target_ou_dn);

			$admin_permission_array = $ou->get_specific_manager_permission_array($admin_dn);


			if ($admin_ou_list=="ou=*") {

				$sr=ldap_search($ds, $target_ou_dn , $uid);

							if ($sr) {

								$customadmin_permission_array = getLdapAttributesList();
							
								$info = ldap_get_entries($ds, $sr);

								$id = 0;

								$json_last_array = Array();

								$user_attributes_array = $info[0];

								foreach ($customadmin_permission_array as $key => $value) {
									
								 	 if ($key!=="count") {

								 	 	$json_last_array[$id] = array("attribute_title"=>ldapinfo::getLdapAttributesMap()[$value].":","attribute_name"=>$value,"attribute_value"=>$user_attributes_array[$value][0]);
								 	 	$id++;

								 	 }

								}

								$output_array = array("status"=>"true","permission_array"=>$customadmin_permission_array,"permissions"=>$json_last_array);

								echo json_encode($output_array);

							ldap_close($ds);

							}else{

								$respons_error_array = Array("status"=>"false1");
								echo json_encode($respons_error_array);

							}


				} elseif(in_array($parent_ou , $admin_ou_array)) {
						
						$sr=ldap_search($ds, $target_ou_dn , $uid);

							if ($sr) {
							
								$info = ldap_get_entries($ds, $sr);

								$id = 0;

								$json_last_array = Array();

								$user_attributes_array = $info[0];

								foreach ($admin_permission_array as $key => $value) {
									
								 	 if ($key!=="count") {

								 	 	$json_last_array[$id] = array("attribute_title"=>ldapinfo::getLdapAttributesMap()[$value],"attribute_name"=>$value,"attribute_value"=>$user_attributes_array[$value][0]);
								 	 	$id++;

								 	 }

								}

								$output_array = array("status"=>"true","permission_array"=>$admin_permission_array,"permissions"=>$json_last_array);

								echo json_encode($output_array);

							ldap_close($ds);

							}else{

								$respons_error_array = Array("status"=>"false1");
								echo json_encode($respons_error_array);

							}

					}else{

						$respons_error_array = Array("status"=>"false2");
						echo json_encode($respons_error_array);

					}

			}

		public static function xlshandle($request){

			header('Content-type: application/json');

			$ds=connect::ldapconnectandbind();

			$output_dir = "lib/uploadxls/";

			$externaloufromui = $request['myou'];

			if ($request["myfile"]["error"] > 0)
				{
				  echo "Error: " . $request["myfile"]["error"] . "<br>";
				}
				else
				{
					$filename = $request["myfile"]["name"];
			    	if (move_uploaded_file($request["myfile"]["tmp_name"],$output_dir . $filename)) {
			    		
			    		$address = $output_dir.$filename; 

						$respons_error_array = Array("0"=>Array("status"=>"false"));

						 $inputFileName = $output_dir.$filename;

						 $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);

						 $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,false);
						   					
						 $id = 0;
						 $output_user_array = Array();

						foreach ($sheetData as $key => $value) {
							    $user_array = create_user_array($value[0],$value[1],$value[2],$value[3],$externaloufromui);
							    $explode = explode("," , $user_array[0]);
				 	 			$output_ou =  $explode[0] . "," . $explode[1];

								 if (user_add($user_array[0],$user_array[1])) {
								 	$output_user_array[$id] = Array("ou"=>$output_ou,"name"=>$value[0],"pass"=>$value[1],"email"=>$value[2]);
								 	$id++;
								 	continue;
								 }else{
								    echo json_encode($respons_error_array);
									die();
								 };
						};

			echo json_encode(get_response_success($output_user_array)); 
			    	
			    	}else echo "noooooo";
			    	
				};

		}

		public static function treenodes($ou){

			header('Content-type: application/json');

			$ldap_info = ldapinfo::get_ldap_info();

			$ds=connect::ldapconnectandbind();

			$pos = strpos($ldap_info["mainserverrdn"], ',', 0);
			$secparentan = substr($ldap_info["mainserverrdn"], 0 , $pos);

			if ($ou=="ou=*") {

				$sr=ldap_search($ds, $ldap_info["mainserverrdn"], $ldap_info["oustar"]);
					$srp=ldap_list($ds, $ldap_info["mainserverrdn"], $ldap_info["oustar"]);

					if ($sr) {
					
					$info = ldap_get_entries($ds, $sr);
					$infop = ldap_get_entries($ds, $srp);
					$id = 1;
					$json_last_array = Array();
					$first_level_array = Array();
					$first_level_id = 1;
					$json_last_array["0"] = Array ( "id" => "0" ,"parent" => "#" ,"text" => "fci.co.ir" ,"icon" => "views/img/ldap-ou.png" , "state" => Array ( "selected" => 1 ,"opened" => 1 ) ); 
							
							foreach ($infop as $keyp => $valuep) {

								if ($keyp!=="count") {	 	 

									$rdn = get_rdn_from_dn($valuep["dn"]);

									$first_level_array[$rdn] = $first_level_id;

									$first_level_id++;

									$json_last_array[$id] = Array ( "id" => $id ,"parent" => "0" ,"text" => get_name_from_dn($valuep["dn"]) ,"icon" => "views/img/ldap-ou.png" , "state" => Array ("opened" => 1 ) ); 
								
									$id++;

								}
							}

							foreach ($info as $key => $value) {

						 	 if ($key!=="count") {	 	 

						 				if (get_parent_from_dn($value["dn"])===$secparentan) {

						 					continue;

						 	 			}else{

						 	 			$json_last_array[$id] = Array ( "id" => $id ,"parent" => $first_level_array[get_parent_from_dn($value["dn"])] ,"text" => get_name_from_dn($value["dn"]) ,"icon" => "views/img/ldap-ou.png"); 
						 	 			$id++;

						 	 			}

						 						 }else{
											 	 	continue;
											 	 }
							}

							echo json_encode($json_last_array);

							ldap_close($ds);
					}
			}

					else

					{

						$explode_ous = explode("," , $ou);					

						$ou_id = 2;
						$bundle_id = 0;

						$json_last_array[] = Array ( "id" => "1" ,"parent" => "#" ,"text" => "Base" ,"icon" => "views/img/ldap-ou.png" , "state" => Array ( "selected" => 1 ,"opened" => 1 ) );

						foreach ($explode_ous as $key1 => $value1) {
							$json_last_array[] = Array ( "id" => $ou_id ,"parent" => "1" ,"text" => substr($explode_ous[$bundle_id],3) ,"icon" => "views/img/ldap-ou.png");
							$ou_id++;
							$bundle_id++;
						} 

						echo json_encode($json_last_array);

					}

		}

		public static function adminmanagment($ou,$user_name){
		
			header('Content-type: application/json');

				$ldap_info = ldapinfo::get_ldap_info();
			
				if ($ou == "ou=*") {
					
						$management_ok_array = Array("managment"=>"ok","management_name"=>$user_name,"management_ou_btn"=>"<a class='btn btn-success btn-small btn-ou' href='#'>مدیریت گروه ها</a>","management_ou_popup"=>"
											<div class='modify-write'>
									            <h3>مدیریت گروه ها:</h3>
									            <div class='ou_ou_table_handler'>
										            <table class='table table-bordered table-striped'>
													  <thead>
													      <tr>
													        <th>نام گروه:</th>
													        <th>عملگر:</th>
													      </tr>
													    </thead>
													    <tbody id='table-bundle'>
													    </tbody>
													 </table>
												 </div>
									            <p class='popup-btn-box'>
									            <button class='btn btn-primary ou_delete'>
									                    حذف گروه
									            </button>
									            <button class='btn btn-primary no'>
						          				  لغو
						                		 </button>
									            </p>
									        </div>
									        <div class='modify-left'>
									            <h3>تعیین سقف ظرفیت گروه:</h3>
									            <br/>
									        	<p> 
						            			 <input type='number' id='ou_capacity' name='ou_capacity' class='ou_capacity input-xlarge' disabled />
						            			 <br/>
									            <button class='btn btn-primary ou_capacity_btn'>
									                    ثبت
									            </button>
									            <button class='btn btn-primary no'>
						          				  لغو
						                		 </button>
									            </p>
									            <h3>اضافه کردن گروه:</h3>
									            <p>
						            			 <p>نام انتخابی:</p> 
						            			 <input type='text' id='ou_add' name='ou_name' class='add_ou input-xlarge' value='' />
									            <button class='btn btn-primary ou_add_btn'>
									                    اضافه کردن گروه جدید
									            </button>
									            <button class='btn btn-primary no'>
						          				  لغو
						                		 </button>
									            </p>
									        </div>" ,
								            "management_manager_wizard_trigger" => "<h3>تفویض مدیریت به کاربر:</h3>
					            			 <p>لطفأ برای تفویض مدیریت به کاربر روی دکمه زیر کلیک بفرمایید.</p>
					            			 <p class='popup-btn-box'>
					                		 <button id='wizard_btn' class='btn btn-large btn-danger' type='submit' value='submit'>
												قراردادن به عنوان مدیر
					                		 </button>
					            			 </p>",
					            			 "dropdown"=>'<li class="dropdown">
								              <a href="#" class="dropdown-toggle" data-toggle="dropdown">ابزارهای مدیریتی<b class="caret"></b></a>
								              <ul class="dropdown-menu">
								                <li><a class="btn-ou" href="#">مدیریت گروه ها</a></li>
								                <li><a class="btn-managers-pupup-trigger" href="#">مدیریت مدیران</a></li>
								              </ul>
								            </li>',
								            "manager_permissions_edit"=>'<p>موارد سطح دسترسی کاربر:</p><i class="ou_manager_name"></i>
											<p>در گروه:</p><i class="manager_ou_name"></i>
											<label class="checkbox" data-attrname="cn">
							                        <input class="attributes_checkbox" type="checkbox" data-attrname="cn" name="cn" value="true">
							                        <p>نام کامل</p>
						                    </label>
						                    <label class="checkbox" data-attrname="mail">
							                        <input class="attributes_checkbox" type="checkbox" data-attrname="mail" name="mail" value="true">
							                        <p>آدرس ایمیل</p>
						                    </label>
						                    <label class="checkbox" data-attrname="userpassword">
							                        <input class="attributes_checkbox" type="checkbox" data-attrname="userpassword" name="userpassword" value="true">
							                        <p>کلمه عبور</p>
						                    </label>
						                    <label class="checkbox" data-attrname="sn">
							                        <input class="attributes_checkbox" type="checkbox" data-attrname="sn" name="sn" value="true">
							                        <p>شناسه</p>
						                    </label>
						                    <label class="checkbox" data-attrname="zarafaaccount">
							                        <input class="attributes_checkbox" type="checkbox" data-attrname="zarafaaccount" name="zarafaaccount" value="true">
							                        <p>فعال بودن اکانت</p>
						                    </label>
						                    <label class="checkbox" data-attrname="zarafaadmin">
							                        <input class="attributes_checkbox" type="checkbox" data-attrname="zarafaadmin" name="zarafaadmin" value="true">
							                        <p>مدیریت</p>
						                    </label>
						                    <label class="checkbox" data-attrname="zarafasmsalert">
							                        <input class="attributes_checkbox" type="checkbox" data-attrname="zarafasmsalert" name="zarafasmsalert" value="true">
							                        <p>پیامک هشدار ورود به سیستم</p>
						                    </label>
						                    <label class="checkbox" data-attrname="zarafaquotahard">
							                        <input class="attributes_checkbox" type="checkbox" data-attrname="zarafaquotahard" name="zarafaquotahard" value="true">
							                        <p>فضا به مگابایت</p>
						                    </label>
						                    <label class="checkbox" data-attrname="zarafaquotasoft">
							                        <input class="attributes_checkbox" type="checkbox" data-attrname="zarafaquotasoft" name="zarafaquotasoft" value="true">
							                        <p>هشدار اتمام حجم</p>
						                    </label>
						                    <label class="checkbox" data-attrname="zarafaquotawarn">
							                        <input class="attributes_checkbox" type="checkbox" data-attrname="zarafaquotawarn" name="zarafaquotawarn" value="true">
							                        <p>اخطار تمام شدن فضا</p>
						                    </label>
						                    <label class="checkbox" data-attrname="zarafasharedstoreonly">
							                        <input class="attributes_checkbox" type="checkbox" data-attrname="zarafasharedstoreonly" name="zarafasharedstoreonly" value="true">
							                        <p>قفل شدن اکانت</p>
						                    </label>
						                    <p>
								            <button class="btn btn-primary admin-save-change-permisson" disabled="disabled">
								                    اعمال شود
								            </button>
								            <button class="btn btn-primary">
								                    لغو
								            </button>
								            </p>',
								            "dashboard_link"=>'<li class="active"><a href="'. $ldap_info['mainpageurl'] .'?page=dashboard">داشبورد</a></li>');


					echo json_encode($management_ok_array);

				}else{

						$management_nok_array = Array("managment"=>"nok","management_name"=>$user_name);

						echo json_encode($management_nok_array);


				}

			}

		
			public static function wizardcontent($ou,$request){

				header('Content-type: application/json');
				
				if($ou=="ou=*"){
					
					$ds = connect::ldapconnectandbind();

					$ldap_info = ldapinfo::get_ldap_info();

					$incomplite_dn = $request['incomplitedn'];

					$complite_dn = $incomplite_dn . "," . $ldap_info['mainserverrdn'];

					$user_name = $request['cn'];

					$first_level=ldap_list($ds, $ldap_info["mainserverrdn"] , "ou=*");

					if ($first_level) {
			
						$first_level_array = ldap_get_entries($ds, $first_level);

						$ou_id = 0;
						$attr_id = 0;
						$level_number = 0;
						$ou_array = Array();
						$attr_array = Array();
						
						foreach ($first_level_array as $key => $value) {

							if($key!=="count"){

							$dn = $value["dn"];
				
							$sr = ldap_search($ds, $dn , "ou=*");
				
							if($sr){

							$info = ldap_get_entries($ds, $sr);

								foreach ($info as $key1 => $value1) {
								
									if($key1!=="count"){
										if(!check_dn_in_ldap_array($value1['dn'], $first_level_array)){
											if (!check_ou_maneger($complite_dn,$value1)) {
												$ou_array[$ou_id]=array("name"=>get_name_from_dn($value1['dn']),"dn"=>$value1['dn']);
												$ou_id++;
											}
										};
									} 

								}

							}
			
							}
						}
						foreach (ldapinfo::getLdapAttributesMap() as $key2 => $value2) {
									$attr_array[$attr_id]=array("name"=>$value2,"dataname"=>$key2);
									$attr_id++;
								}
					}

						$ok_response_array = array("status"=>"true","radiobuttons"=>$ou_array,"checkboxes"=>$attr_array,"username"=>$user_name,"userdn"=>$complite_dn);

						echo json_encode($ok_response_array);

				}else{						
						$nok_response_array = array("status"=>"false");					
						echo json_encode($nok_response_array);
				}
			}

			public static function wizardhtml($ou){

				header('Content-type: application/json');
				
				if($ou=="ou=*"){
				 $html_array = array("status"=>"true","wizard_html"=>"
					<h2>توضیحات</h2>
								                <section>
								                    <p>با سلام</p>
								                    <p>این پنجره برای قرار دادن کاربر :</p>
								                    <i id='manager_name'></i>
								                    <span id='hide_dn' style='display:none'></span>
								                    <p>در لیست مدیران این سامانه مدیریتی قرار دارد.</p>
								                    <p>در این قسمت با طی کردن چند مرحله شما کاربر انتخابی را به عنوان مدیر ساماه در نظر میگیرید.</p>
								                    <p>تمام مدیران با استفاده از نام کاربری و کلمه عبور ایمل خود قابلیت ورود به سامانه مدیریت را خواهند داشت.</p>
								                    <p>با گذراندن مراحل تعیین مدیر شما موارد زیر را برای سطح دسترسی مدیر مشخص میکنید:</p>
								                    <p>اول: تعیین گروه تحت مدیریت مدیر. در این بخش شما مشخص میکنید مدیر تعیین شونده به چه گروه دسترسی مدیریتی دارد.</p>
								                    <p>دوم: تعیین موارد دسترسی به مشخصه های کاربران. در این بخش شما مشخص میکنید مدیر تعیین شونده به چه مشخصه هایی از اطلاعات کاربران دسترسی مدیریتی دارد.</p>
								                    <p>لطفأ برای ادامه روی دکمه «بعدی» کلیک بفرمایید.</p>
								                </section>

								                <h2>انتخاب گروه</h2>
								                <section>
								                    <p>لطفأ یکی از گروه های کاربری زیر را برای باز کردن دسترسی برای مدیر انتخاب بفرمایید :</p>
								                    <div class='control-group'>
								                        <label class='control-label'>گروه انتخابی:</label>
								                        <div class='controls controls-radio'>
								                         
								                        </div>
								                      </div>
								                      <p class='help-block'><strong>توجه:</strong> لطفأ پس از انتخاب گروه روی دکمه بعدی کلیک بفرمایید.</p>
								                </section>

								                <h2>سطح دسترسی</h2>
								                <section>
								                    <p>لطفأ یکی از گروه های کاربری زیر را برای باز کردن دسترسی برای مدیر انتخاب بفرمایید و سپس روی دکمه بعدی کلیک بفرمایید.</p>
								                    <label class='control-label'>مشخصه های انتخابی:</label>
								                    <div class='controls controls-checkbox'>
								                     								                      <p class='help-block'><strong>توجه:</strong>لطفأ پس از انتخاب مشخصه های مد نظر روی دکمه بعدی کلیک بفرمایید.</p>
								                    </div>
								                </section>

								                <h2>تأیید نهایی</h2>
								                <section>
								                    <p>لطفأ با مشاهده اطلاعات انتخابی در مورد صحت این اطلاعات اطمینان کامل کسب بفرمایید و در صورت تمایل به تصحیح با کلیک بر دکمه قبلی به مراحل قبل باز گردید.</p>
								                    <table class='table table-bordered table-striped'>
								                        <thead>
								                          <tr>
								                            <th>مشخصه انتخابی</th>
								                            <th>مقدار انتخابی</th>
								                          </tr>
								                        </thead>
								                        <tbody>
								                          <tr>
								                            <td>گروه اختصاص داده شده</td>
								                            <td class='manager_group_name'></td>
								                          </tr>
								                          <tr>
								                            <td>مشخصه های اختصاص داده شده</td>
								                            <td class='manager_attribute_name'></td>
								                          </tr>
								                        </tbody>
								                      </table>
								                      <p class='help-block'><strong>توجه:</strong> اطلاعات انتخاب شده پس از فشردن دکمه ثبت در سامانه زخیره میشود. فلذا لطفأ قبل از فشردن دکمه ارسال از صحت اطلاعات ارسالی اطمینان حاصل کنید. سپس روی دکمه بعدی کلیک بفرمایید.</p>
								                </section>	
				");
				
				echo json_encode($html_array);
				
				}else{
					$error_array= array("status"=>"false");
				echo json_encode($error_array);
				};		
			}

			public static function setadmin($user_ou,$request){

				header('Content-type: application/json');
				
				if($user_ou=="ou=*"){

					$admin_values = json_decode($request['admin_values'],true);

					$destination_ou_dn = $request['target_ou'];

					$ou = get_ou_object_from_dn($destination_ou_dn);

					$ou->add_manager($admin_values);

					if ($ou->save()&&set_zarafa_admin($admin_values['manager_dn'])) {
						$success_array = array("status"=>"true");
						echo json_encode($success_array);
					}else{
						$error_array = array("status"=>"false");
						echo json_encode($error_array);
						return;
					}

				}else{
						$error_array = array("status"=>"false");
						echo json_encode($error_array);
						return;
					}

			}

			public static function updateadminpermission($user_ou,$request){

				header('Content-type: application/json');
				
				if($user_ou=="ou=*"){

					$destination_ou_dn = $request['destination_ou_dn'];

					$admin_dn = $request['admin_dn'];

					$admin_values = json_decode($request["new_permissions"]);

					$ou = get_ou_object_from_dn($destination_ou_dn);

					$ou->update_manager_permossion($admin_dn,$admin_values);

					if ($ou->save()) {
						$success_array = array("status"=>"true");
						echo json_encode($success_array);
					}else{
						$error_array = array("status"=>"false");
						echo json_encode($error_array);
						return;
					}

				}else{
						$error_array = array("status"=>"false");
						echo json_encode($error_array);
						return;
					}

				}

			public static function deleteadmin($user_ou,$destination_ou_dn,$admin_dn){

				header('Content-type: application/json');
				
				if($user_ou=="ou=*"){

					$ou = get_ou_object_from_dn($destination_ou_dn);

					$ou->delete_manager($admin_dn);

					if ($ou->save()) {
						$success_array = array("status"=>"true");
						echo json_encode($success_array);
					}else{
						$error_array = array("status"=>"false");
						echo json_encode($error_array);
						return;
					}

				}else{
						$error_array = array("status"=>"false");
						echo json_encode($error_array);
						return;
					}

				}

			public static function manegerpopupcontent($user_ou){

				header('Content-type: application/json');

				if($user_ou=="ou=*"){

					$ldap_info = ldapinfo::get_ldap_info();

					$ds=connect::ldapconnectandbind();

					$id = 1;

					$in_level_maneger_list_array = array();

					$managers_array = array();

					$last_array = array();

					$srp=ldap_list($ds, $ldap_info["mainserverrdn"], $ldap_info["oustar"]);

					$sr=ldap_search($ds, $ldap_info["mainserverrdn"], $ldap_info["oustar"]);

					$infop = ldap_get_entries($ds, $srp);

					$info = ldap_get_entries($ds, $sr);

					  foreach ($info as $key => $value) {

					  	if ($key!=="count") {
					  		
					  		if (!in_array($value, $infop)) {		

					  			$check_array = array();

					  			$in_level_maneger_list_array = array();

					  			$helper_id = 0;  			

					  			$descr_array = json_decode($value['description'][0],true);

					  			$managers_array = $descr_array['managers'];

					  			foreach ($managers_array as $key1 => $value1) {

									empty($in_level_maneger_list_array);
									
					  				if (!in_array($value1['manager_dn'], $check_array)) {
					  				
					  					$in_level_maneger_list_array[] = "<p data-manegerdn='" . $value1['manager_dn'] . "'>" . get_cn_by_dn($value1['manager_dn']) . "</p>";

					  					$check_array[$helper_id] = $value1['manager_dn'];

					  					$helper_id++;

					  				}
 
					  			}

					  			$last_array[$id] = array("ou_dn"=>$value['dn'],"ou_name"=>substr(get_rdn_from_dn($value['dn']), 3),"manegers_list_html"=>implode(" ", $in_level_maneger_list_array));
					  			$id++;

					  		}
					  	 }
					  }

					  $response_array = array("status"=>"true","content"=>$last_array);
					  	echo json_encode($response_array);  

					}else{
						$error_array = array("status"=>"false");
						echo json_encode($error_array);
						return;
					}		

			}

			public static function oumanegerpopupcontent($user_ou,$request){

				header('Content-type: application/json');

				if($user_ou=="ou=*"){

					$ldap_info = ldapinfo::get_ldap_info();

					$ds = connect::ldapconnectandbind();

					$ou = $request['ou_dn'];

					$ou_rdn = get_rdn_from_dn($ou);

					$sr = ldap_search($ds, $ldap_info["mainserverrdn"], $ou_rdn);

					$info = ldap_get_entries($ds, $sr);

					$permission_html_array = array();

					$check_array = array();

					$managers_array = json_decode($info[0]['description'][0],true)['managers'];

					$id=0;

					$last_array = array();

					foreach ($managers_array as $key1 => $value1) {
							
			  				if (!in_array($value1['manager_dn'], $check_array)) {

			  					unset($permission_html_array);

			  					$check_array[$id] = $value1['manager_dn'];

			  					$cn = get_cn_by_dn($value1['manager_dn']);

			  					foreach ($value1['manager_permissions'] as $key => $value) {
			  						
			  						$permission_html_array[] = "<p data-manegerpermission='" . $value . "'>" . ldapinfo::getLdapAttributesMap()[$value] . "</p>";

			  					}

			  					$last_array[$id] = array("maneger_dn"=>$value1['manager_dn'],"maneger_name"=>$cn,"manegers_permission_html"=>implode(" ", $permission_html_array));

			  					$id++;

			  				}
 
					  }

						$response_array = array("status"=>"true","content"=>$last_array);
					  	echo json_encode($response_array);  

					}else{
						$error_array = array("status"=>"false");
						echo json_encode($error_array);
						return;
					}		

			}

			public static function setoumax($user_ou,$request){

				header('Content-type: application/json');

				if($user_ou=="ou=*"){

					$ldap_info = ldapinfo::get_ldap_info();

					$target_ou_dn = $request['target_ou'];

					$new_max_number = (int) $request['new_max'];
				
					$ou = get_ou_object_from_dn($target_ou_dn);

					$ou->update_max($new_max_number);

					if ($ou->save()) {
						$success_array = array("status"=>"true");
						echo json_encode($success_array);
					}else{
						$error_array = array("status"=>"false");
						echo json_encode($error_array);
						return;
					}

				}else{
						$error_array = array("status"=>"false");
						echo json_encode($error_array);
						return;
					}

			}

			public static function numberofallaccounts($user_ou,$internal_usage=false){

				header('Content-type: application/json');

				if($user_ou=="ou=*"){

					$ldap_info = ldapinfo::get_ldap_info();

					$ds = connect::ldapconnectandbind();

					$dn = $ldap_info["mainserverrdn"];

					$sr = ldap_search($ds, $dn, "uid=*");

					if ($internal_usage) {
						
						return ldap_count_entries($ds, $sr);						

					}

				    $response_array = array("status"=>"true","content"=>ldap_count_entries($ds, $sr));
					echo json_encode($response_array);
					return;

				}else{

					$error_array = array("status"=>"false");
					echo json_encode($error_array);
					return;

				}

			}

			public static function numberofallgroups($user_ou){

				header('Content-type: application/json');

				if($user_ou=="ou=*"){

					$ldap_info = ldapinfo::get_ldap_info();

					$ds = connect::ldapconnectandbind();

					$main_dn = $ldap_info["mainserverrdn"];

					$sr = ldap_search($ds, $main_dn , "ou=*");

					$info = ldap_get_entries($ds, $sr);

					$last_level_array = array();

					$last_number = 0;

					foreach ($info as $key => $value) {
						
					  	if (($key!=="count")&&(get_parent_from_dn($value["dn"])!="dc=elenoon")) {

					  		$last_number++;

						}

					}

					 $response_array = array("status"=>"true","content"=>$last_number);
					echo json_encode($response_array);
					return;

					}else{

					$error_array = array("status"=>"false");
					echo json_encode($error_array);
					return;

					}								

			}

			public static function numberoflocaladmins($user_ou){

					header('Content-type: application/json');

				if($user_ou=="ou=*"){

					$ldap_info = ldapinfo::get_ldap_info();

					$ds=connect::ldapconnectandbind();

					$id = 1;

					$in_level_maneger_list_array = array();

					$managers_array = array();

					$last_array = array();

					$last_number_of_managers = 0;

					$srp=ldap_list($ds, $ldap_info["mainserverrdn"], $ldap_info["oustar"]);

					$sr=ldap_search($ds, $ldap_info["mainserverrdn"], $ldap_info["oustar"]);

					$infop = ldap_get_entries($ds, $srp);

					$info = ldap_get_entries($ds, $sr);

					  foreach ($info as $key => $value) {

					  	if (($key!=="count")&&(!in_array($value, $infop))) {
					  		
					  			$ou = get_ou_object_from_ou_array($value);
					  			$last_number_of_managers = $last_number_of_managers + $ou->get_number_of_managers();

					  	}

					  }

					$response_array = array("status"=>"true","content"=>$last_number_of_managers);
					echo json_encode($response_array);
					return;

					}else{

					$error_array = array("status"=>"false");
					echo json_encode($error_array);
					return;

					}


			}

			public static function allusercapacity($user_ou){

				header('Content-type: application/json');

				if($user_ou=="ou=*"){

					$ldap_info = ldapinfo::get_ldap_info();

					$response_array = array("status"=>"true","content"=>$ldap_info['allusercapacity']);

					echo json_encode($response_array);
					return;

				}
				else{

					$response_array = array("status"=>"false");
					echo json_encode($response_array);
					return;

				}

			}

			public static function allsystemquota($user_ou){

				header('Content-type: application/json');

				if($user_ou=="ou=*"){

					$ldap_info = ldapinfo::get_ldap_info();

					$response_array = array("status"=>"true","content"=>$ldap_info['allsystemquota']);
					echo json_encode($response_array);
					return;

				}
				else{

					$response_array = array("status"=>"false");
					echo json_encode($response_array);
					return;

				}

			}

			public static function systemfreeusercapacity($user_ou){

				header('Content-type: application/json');

				if($user_ou=="ou=*"){
				 
					$ldap_info = ldapinfo::get_ldap_info();

					$ds = connect::ldapconnectandbind();

					$dn = $ldap_info["mainserverrdn"];

					$sr = ldap_search($ds, $dn, "uid=*");

				    $numberofusers = ldap_count_entries($ds, $sr);

				    $numberoffreeusers = (int)$ldap_info['allusercapacity'] - $numberofusers;

				    $percent = ($numberoffreeusers/(int)$ldap_info['allusercapacity'])*100;

				    $response_array = array("status"=>"true","content"=>array("number"=>$numberoffreeusers,"percent"=>$percent));
					echo json_encode($response_array);
					return;

				}else{

					$response_array = array("status"=>"false");
					echo json_encode($response_array);
					return;

				}

			}

			public static function systemusedquota($user_ou,$internal_usage=false){

				header('Content-type: application/json');

				if($user_ou=="ou=*"){
				 
					$ldap_info = ldapinfo::get_ldap_info();

					$ds = connect::ldapconnectandbind();

					$sr = ldap_search($ds, $ldap_info["mainserverrdn"] , "uid=*");

					$used_capacity = 0;

					$info = ldap_get_entries($ds, $sr);

					foreach ($info as $key => $value) {
						
					 	 if ($key!=="count") {

					 	 	$used_capacity += (int)$value['zarafaquotahard'];

					 	 }

					}

					if($internal_usage){

						return $used_capacity;
						
					}

					$percent = ($used_capacity/(int)$ldap_info['allsystemquota'])*100;
					$response_array = array("status"=>"true","content"=>array("number"=>$used_capacity,"percent"=>$percent));
					echo json_encode($response_array);
					return ;

				}
				else{

					$response_array = array("status"=>"false");
					echo json_encode($response_array);
					return;

				}

			}

			public static function systemfreequota($user_ou){

				header('Content-type: application/json');

				if($user_ou=="ou=*"){
				 
					$ldap_info = ldapinfo::get_ldap_info();

					$used_capacity = ldapaction::systemusedquota($user_ou,true);

					$freecapacity = (int)$ldap_info['allsystemquota'] - $used_capacity;

					$percent = ($freecapacity/(int)$ldap_info['allsystemquota'])*100;

					$response_array = array("status"=>"true","content"=>array("number"=>$freecapacity,"percent"=>$percent));
					echo json_encode($response_array);
					return;

				}else{

					$response_array = array("status"=>"false");
					echo json_encode($response_array);
					return;

				}

			}

			public static function systemusedusercapacity($user_ou){

				header('Content-type: application/json');

				if($user_ou=="ou=*"){

					$ldap_info = ldapinfo::get_ldap_info();

					$ds = connect::ldapconnectandbind();

					$dn = $ldap_info["mainserverrdn"];

					$sr = ldap_search($ds, $dn, "uid=*");

					$numberofusers = ldap_count_entries($ds, $sr);
				    $percent = ($numberofusers/(int)$ldap_info['allusercapacity'])*100;
		    	    $response_array = array("status"=>"true","content"=>array("number"=>$numberofusers,"percent"=>$percent));
		    	    echo json_encode($response_array);
		    	    return;

				}else{

					$error_array = array("status"=>"false");
					echo json_encode($error_array);
					return;

				}

			}

			public static function numberofdisableaccounts($user_ou){

				header('Content-type: application/json');

				if($user_ou=="ou=*"){

					$ldap_info = ldapinfo::get_ldap_info();

					$ds = connect::ldapconnectandbind();

					$dn = $ldap_info["mainserverrdn"];

					$sr = ldap_search($ds, $dn, "(&(uid=*)(zarafaaccount=0))");

					$response_array = array("status"=>"true","content"=>ldap_count_entries($ds, $sr));
					echo json_encode($response_array);
					return;

			}
			else{

				$response_array = array("satus"=>"false");
				echo json_encode($response_array);
				return;

			}

		}

			public static function numberoffinishedaccounts($user_ou){

				header('Content-type: application/json');

				if($user_ou=="ou=*"){

					$zcp = new zcpservice();

						if($zcp->zcpconnect()){

							$zcp_users_info = $zcp->zcpgetusersarray();

							$number_of_finished_accounts = 0;

							foreach ($zcp_users_info as $key => $value) {

								if ($value["cap"]==$value["used"]) {

									$number_of_finished_accounts++;
									
								}

							}

						$response_array = array("status"=>"true","content"=>$number_of_finished_accounts);
						echo json_encode($response_array);
						return;

						}
						else{

							$response_array = array("satus"=>"false");
							echo json_encode($response_array);
							return;

					}


				}
				else{

				$response_array = array("satus"=>"false");
				echo json_encode($response_array);
				return;

				}

			}

			public static function numberofloginaccounts($user_ou){

				header('Content-type: application/json');

				if($user_ou=="ou=*"){

				$apacheservice = new apacheservice();

					if($apacheservice->apacheconnect()){

						$response_array = array("status"=>"true","content"=>$apacheservice->getonlinesnumber());
						echo json_encode($response_array);
						return;

					}
					else{

						$response_array = array("satus"=>"false");
						echo json_encode($response_array);
						return;

					}

				}
				else{

					$response_array = array("satus"=>"false");
					echo json_encode($response_array);
					return;

				}
			}

	}

?>
