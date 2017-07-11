<?php
namespace LP\PAGE\OBJECTS;

require_once 'lib/function.php';
require_once 'models/class.ldapinfo.php';
require_once 'models/class.ldapconnect.php';

use LP\LDAP\AUTH\CONNECT\ldapConnector as connect;
use LP\LDAP\AUTH\CONNECT\ldapinfo as ldapinfo;
use \PHPExcel_IOFactory as PHPExcel_IOFactory;
use \ArrayObject as ArrayObject;

ini_set('display_errors',1); 
 error_reporting(E_ALL);

 	/**
 	* 
 	*/
 	class OU {

 		private $dn = "";

 		private $full_array = array();
 		
 		private $full_array_object = null;

 		private $descr_array = array();
 		
 		private $descr_array_object = null;

 		private $gid_number = "";

 		private $max_capacity = "";
 		
 		private $ou_usable_name = "";
 		
 		private $managers_dn_list = array();

 		private $managers_array = array();


 		function __construct($argument){

 			$input_object = new ArrayObject($argument);

 			$this->dn = $input_object['dn'];

 			$this->full_array = (array) $input_object;

 			$this->full_array_object = $input_object;

 			$this->descr_array = (array) $input_object['descr'];

 			$this->descr_array_object = $input_object['descr'];

 			$this->gid_number = $input_object['descr']->gid_number;

 			$this->max_capacity = $input_object['descr']->max;

 			$this->ou_usable_name =  get_name_from_dn($input_object['dn']);

 			$this->managers_dn_list = managers_dn_list_for_ou_class((array)$input_object['descr']);

 			$this->managers_array = manager_array_for_ou_class((array)$input_object['descr']->managers);

 		}



 		public function get_dn(){
 				return $this->dn;
 		}

 		public function get_max(){
 				return $this->max_capacity;
 		}

 		public function get_gid_number(){
 				return $this->gid_number;
 		}

 		public function get_managers_dn_list(){
 				return $this->managers_dn_list;
 		}

 		public function get_number_of_managers(){
 				$local_manager_array = $this->managers_dn_list;
 				return count($local_manager_array);
 		}

 		public function get_specific_manager_array($manager_dn){
 			foreach ($this->managers_array as $key => $value) {
 				if($value['manager_dn']==$manager_dn){
 					return $value;
 				}
 			}
 				return 0;
 		}

 		public function get_specific_manager_permission_array($manager_dn){
 			$local_manager_array = $this->get_specific_manager_array($manager_dn);
 				return $local_manager_array['manager_permissions'];
 		}

 		public function update_max($max_number){
 			$this->max_capacity = $max_number;
 		}

 		public function add_manager($add_manager_array){
 			$new_index = count($this->managers_array);
 			$this->managers_array[$new_index] = $add_manager_array;
 		}

 		public function update_manager_permossion($manager_dn,$new_permission_array){
 			foreach ($this->managers_array as $key => $value) {
 				if ($value['manager_dn']==$manager_dn) {
 					$this->managers_array[$key]['manager_permissions']=$new_permission_array;
 					break ;
 				}
 			}
 		}

 		public function delete_manager($manager_dn){
 			foreach ($this->managers_array as $key => $value) {
 				if ($value['manager_dn']==$manager_dn) {
 					$this->managers_array[$key]="deleted";
 					break ;
 				}
 			}
 		}

 		public function save(){
 			$output_array = array("dn"=>$this->dn,"descr"=>array("gid_number"=>$this->gid_number,"max"=>$this->max_capacity,"managers"=>$this->managers_array));
 			return ou_modify($output_array);
 		}


 	}

 ?>