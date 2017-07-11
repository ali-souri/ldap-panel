<?php
namespace LP\PAGE;

 require 'models/class.ldapinfo.php';
 require 'models/class.ldapaction.php';

 use LP\LDAP\AUTH\CONNECT\ldapinfo as ldapinfo;

ini_set('display_errors',1); 
 error_reporting(E_ALL);

class content {

	public static function handlejsonrequest($requesttype,$request,$user_ou,$user_name,$user_dn){

		if ($requesttype=="getnodecontent") {

			JSON\ldapaction::nodecontent($request);
		
		}elseif ($requesttype == "ouadd") {

			JSON\ldapaction::ouadd($request,$user_ou);

		}elseif ($requesttype == "oudelete") {

			JSON\ldapaction::oudelete($request,$user_ou);

		}elseif ($requesttype == "oulist") {

			JSON\ldapaction::oulist($user_ou);

		}elseif ($requesttype=="treenode") {
		
			JSON\ldapaction::treenodes($user_ou);
		
		}elseif ($requesttype=="useradd") {
		
			JSON\ldapaction::useradd($request);
		
		}elseif ($requesttype=="userdelete") {
		
			JSON\ldapaction::userdelete($request);
		
		}elseif ($requesttype=="usermodify") {
		
			JSON\ldapaction::usermodify($request);
		
		}elseif ($requesttype=="checkinputunique") {
		
			JSON\ldapaction::checkinputunique($request);
		
		}elseif ($requesttype=="modify_attributes") {
		
			JSON\ldapaction::getmodifyattributes($user_ou,$user_dn,$request);
		
		}elseif ($requesttype=="xls") {
		
			JSON\ldapaction::xlshandle($request);
		
		}elseif ($requesttype=="managment") {
		
			JSON\ldapaction::adminmanagment($user_ou,$user_name);
		
		}elseif ($requesttype=="wizard_content") {
		
			JSON\ldapaction::wizardcontent($user_ou,$request);
		
		}elseif ($requesttype=="wizard_html") {
		
			JSON\ldapaction::wizardhtml($user_ou);
		
		}elseif ($requesttype=="admin_add") {
		
			JSON\ldapaction::setadmin($user_ou,$request);
		
		}elseif ($requesttype=="maneger_popup_content") {
		
			JSON\ldapaction::manegerpopupcontent($user_ou);
		
		}elseif ($requesttype=="ou_manegers_popup_content") {
		
			JSON\ldapaction::oumanegerpopupcontent($user_ou,$request);
		
		}elseif ($requesttype == "dashboard") {

			content::echodashboardpage($user_ou);

		}elseif ($requesttype=="change_user_permissions") {
		
			JSON\ldapaction::updateadminpermission($user_ou,$request);
		
		}elseif ($requesttype=="set_capacity") {
		
			JSON\ldapaction::setoumax($user_ou,$request);
		
		}elseif ($requesttype == "numberofallaccounts") {

			JSON\ldapaction::numberofallaccounts($user_ou,false);

		}elseif ($requesttype == "numberofallgroups") {

			JSON\ldapaction::numberofallgroups($user_ou);

		}elseif ($requesttype == "numberoflocaladmins") {

			JSON\ldapaction::numberoflocaladmins($user_ou);

		}elseif ($requesttype == "allusercapacity") {

			JSON\ldapaction::allusercapacity($user_ou);

		}elseif ($requesttype == "allsystemquota") {

			JSON\ldapaction::allsystemquota($user_ou);

		}elseif ($requesttype == "systemfreeusercapasity") {

			JSON\ldapaction::systemfreeusercapasity($user_ou);

		}elseif ($requesttype == "systemusedquota") {

			JSON\ldapaction::systemusedquota($user_ou,false);

		}elseif ($requesttype == "systemfreequota") {

			JSON\ldapaction::systemfreequota($user_ou);

		}elseif ($requesttype == "systemusedusercapacity") {

			JSON\ldapaction::systemusedusercapacity($user_ou);

		}elseif ($requesttype == "systemfreeusercapacity") {

			JSON\ldapaction::systemfreeusercapacity($user_ou);

		}elseif ($requesttype == "numberofdisableaccounts") {

			JSON\ldapaction::numberofdisableaccounts($user_ou);

		}elseif ($requesttype == "numberoffinishedaccounts") {

			JSON\ldapaction::numberoffinishedaccounts($user_ou);

		}elseif ($requesttype == "numberofloginaccounts") {

			JSON\ldapaction::numberofloginaccounts($user_ou);

		}else{
		
				echo "Error: Request invalid.";
			  //echo '<META HTTP-EQUIV=Refresh CONTENT="0.01">';
		
		}
	
	}

	public static function echologin(){
		echo'
			<html>
				<head>
				        <link id="rem" rel="stylesheet" href="views/css/sample.css"/>
				        <script src="views/js/styles.js"></script>
					<title>LDAP PANEL</title>
				</head>
				<body>

				                   <form id="login" action="index.php" method="post">
				                    <h1>ورود مدیران</h1>
				                        <fieldset id="inputs">
				                            <input id="username" placeholder="Username" name="username" type="text">   
				                            <input id="password" placeholder="Password" name="password" type="password">
				                        </fieldset>
				                        <fieldset id="actions">
				                            <input id="submit" value="ورود" type="submit">
				                        </fieldset>
				                 </form>

				</body>
			</html> 
		';
	}

	public static function echopanelpage(){

		$ldap_info = ldapinfo::get_ldap_info();
		$atdomain = "@" . $ldap_info["ldapdomain"];
		$mainpageurl = $ldap_info["mainpageurl"];
		echo '
			<!DOCTYPE html>
				<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
				<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
				<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
				<!--[if gt IE 8]><!--> <html class="no-js"><!--<![endif]-->
				<head>
					<meta charset="utf-8">
					<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
					<title>مدیریت کاربران</title>
					<meta name="viewport" content="width=device-width" />
					<!--[if lt IE 9]><script src="./assets/html5.js"></script><![endif]-->
					
					<meta name="robots" content="index,follow" />
					<link rel="stylesheet" href="./views/assets/bootstrap/css/bootstrap.min.css" />
					<link rel="stylesheet" href="./views/assets/dist/themes/default/style.min.css" />
					<link rel="stylesheet" href="./views/assets/docs.css" />
					<link rel="stylesheet" href="./views/assets/uikit-.1.0.1.min.css" />
					<link rel="stylesheet" href="./views/assets/cust.css" />
					<!--[if lt IE 9]><script src="./views/assets/respond.js"></script><![endif]-->
					
					<link rel="icon" href="./views/assets/favicon.ico" type="image/x-icon" />
					<link rel="apple-touch-icon-precomposed" href="./views/assets/apple-touch-icon-precomposed.png" />
					<link rel="stylesheet" href="./views/css/normalize.css">
			        <link rel="stylesheet" href="./views/css/main.css">
			        <link rel="stylesheet" href="./views/css/jquery.steps.css">
					<script>window.$q=[];window.$=window.jQuery=function(a){window.$q.push(a);};</script>
				</head>
				<body>
						<div class="navbar">
						  <div class="navbar-inner">
						    <div class="container">
						      <a class="brand" href="#">سامانه مدیریت ایمیل</a>
						      <div class="nav-collapse">
						          <ul class="nav manage-extend">
						          </ul>
						          <ul class="nav pull-left">
						          	<li class="active"><a href="#">جست و جو</a></li>
						            <li class="divider-vertical"></li>
						            <li><a class="btn-exit" href="'.$mainpageurl.'?logout'.'">خروج</a></li>
						          </ul>
						        </div>
						    </div>
						  </div>
						</div>
									<div id="page-content">
										<div class="name_div"><p class="name_title">سلام بر</p><p class="user_name"></p></div>
										<div id="jstree2" class="demo" style="margin-top:2em;"></div>
										<div id="tree-node-content"><table class="tree-node-content-table"><tr><th>Mobile Number</th><th>Email Address</th><th>Full Name</th><th>Action</th></tr></table></div>
									</div>
										<script id="content-template" type="text/content-tmpl">
										   <tr>
										   		<td>{{id}}</td>
										   		<td>{{mobile}}</td>
										   		<td>{{address}}</td>
										   		<td>{{name}}</td>
										   		<td><a data-ou=\'{{ou}}\' class="btn btn-inverse modify-btn">مشاهده</a></td>		
										   		<!--<td><a data-ou=\'{{ou}}\' class="modify-btn"></a><a data-ou=\'{{ou}}\' class="delete-btn"></a></td>-->
										   </tr>
										</script>
										<script id="content-template-helper" type="text/content-tmpl">
										   <tr>
										   		<td>{{id}}</td>
										   		<td>{{mobile}}</td>
										   		<td>{{address}}</td>
										   		<td>{{name}}</td>
										   		<td><a data-ou=\'{{ou}}\' class="btn btn-inverse modify-btn new_modify_{{id}}">مشاهده</a></td>
										   		<!--<td><a data-ou=\'{{ou}}\' class="modify-btn-helper"></a><a data-ou=\'{{ou}}\' class="delete-btn-helper"></a></td>-->
										   </tr>
										</script>
										<script id="content-template-ou" type="text/content-tmpl">
										   <tr data-dn=\'{{dn}}\'>
										   		<td><i class="icon-ok"></i>{{name}}</td>
										   		<td><button class="btn btn-small btn-inverse ou_select" data-capacity=\'{{capacity}}\'>انتخاب</button></td>
										   </tr>
										</script>
										<script id="maneger-group-template" type="text/content-tmpl">
										   <label class="radio">
					                            <input type="radio" name="optionsRadios" value=\'{{dn}}\'>
					                            <p>{{name}}</p>
				                           </label>
										</script>
										<script id="maneger-attribute-template" type="text/content-tmpl">
										   <label class="checkbox">
						                        <input type="checkbox" data-attrname=\'{{dataname}}\' name=\'{{name}}\' value="true">
						                        <p>{{name}}</p>
					                       </label>
										</script>
										<script id="content-template-managers-modify" type="text/content-tmpl">
										   <tr data-oudn=\'{{oudn}}\'>
										   		<td><i>{{ouname}}</i></td>
										   		<td>{{manegers_list_html}}</td>
										   		<td><button class="btn btn-small btn-inverse manegers_select">انتخاب</button></td>
										   </tr>
										</script>
										<script id="content-template-ou-managers-modify" type="text/content-tmpl">
										   <tr data-manegerdn=\'{{manegerdn}}\'>
										   		<td><i>{{manegername}}</i></td>
										   		<td>{{manegers_permission_html}}</td>
										   		<td><button class="btn btn-small btn-inverse ou_maneger_select">انتخاب</button></td>
										   </tr>
										</script>
										<script id="attr-modify-template" type="text/content-tmpl">
												 <p>{{attribute_title}}</p> 
						            			 <input type="text" id=\'{{attribute_name}}-id\' name=\'{{attribute_name}}\' class="modify" value=\'{{attribute_value}}\' />
						            	</script>
										<script>
										$(function () {

											management_panel_handler();
											
											jstree_handling();

											var storage = $.sessionStorage;

											temp = $.trim($("#content-template").html());
											var temp_ou = $.trim($("#content-template-ou").html()),
											temp_helper = $.trim($("#content-template-helper").html());
											var permission_array = new Object;
											var globalindex="0";
											var globalfunctionvar = "0";
											var globalqueuekey = "0";
				        					var globalfrag = "";
				        					var frag = "";
											var globalthat;
										    var globalparent;
										    var globalou;
										    var globalounode;
										     $(".yes-add-user").click(function(){
										     	var name = $("#name").val();
										     	var mobile = $("#mobile").val();
										     	var email = $("#address").val();
										     	var pass = $("#pass").val();
										     	user_add(globalounode,name,mobile,email,pass);
										     });
										     $(".yes-modify").click(function(){
										     	var modify_data = $(".ou_table_handler_modify .modify").serializeArray();
										     	user_modify(globalou,modify_data);
										     });
										     $(".yes-delete").click(function(){
										     	user_delete(globalou);
										     });
										$("div#background , div#background-temp , a.close , button.no , button.no-temp" ).on("click" , function(){
				                            closepopup();
				                        });
										$(".no-temp-refresh").click(function(){
											window.location.href=window.location.href;
										});
										$(".no-alert , .close-alert" ).on("click" , function(){
											$("div.alertbox , div.empty-alert , div.phone-alert , div.email-alert").fadeOut();
										});
										$("#mobile").focus(function() {
											var mobilegrandpa = $(this).parent().parent();
											mobilegrandpa.removeClass("error").removeClass("success").removeClass(".mobile-error-cause");
												if (!$("#address").parent().parent().hasClass("address-error-cause")) {
												$(".yes-add-user").removeAttr("disabled");
												};
											$("#mobile-error-message").fadeOut();
										});
										$("#address").focus(function() {
											var addressgrandpa = $(this).parent().parent();
											addressgrandpa.removeClass("error").removeClass("success").removeClass(".address-error-cause");
												if (!$("#mobile").parent().parent().hasClass("mobile-error-cause")) {
												$(".yes-add-user").removeAttr("disabled");
												};
											$("#address-error-message").fadeOut();
										});
										 $("#mobile , #address").on("change",(function (e) {
											  var id = e.target.id;
											   check_input_unique($("#"+id).val(),id);
										}));

										function set_user_modify_form_data(ouname){
											var attr_tmpl = $.trim($("#attr-modify-template").html());
											$(".ou_table_handler_modify").empty();
											$.post("index.php",
													{action:"modify_attributes",
													 target_ou:ouname},
													 function(data){
													 	if (data.status=="true") {
													 		permission_array = data.permission_array;
													 		 $.each(data.permissions , function(index , object){
						                                   			frag_attr = attr_tmpl.replace(/{{attribute_title}}/ig , object.attribute_title)
						                                   							  .replace(/{{attribute_name}}/ig , object.attribute_name)
						                                   							  .replace(/{{attribute_value}}/ig , object.attribute_value);
			                                   							  if (object.attribute_name=="userpassword") {
																		   var alt_frag = $(frag_attr);
																		   	   alt_frag.eq(2).attr("type","password").val("******");
			                                   								$(".ou_table_handler_modify").append(alt_frag);
			                                   							  }else{
									                                   		$(".ou_table_handler_modify").append(frag_attr);
									                                   	  }
						                                        });
													 	}
													});
										};
										function wizard_btn_run(){
											$("#wizard_btn").click(function(){
												wizard_parts_handling(globalou);
													var manage_manager = $("div.manage_manager");
													showpopup(manage_manager);
											});
										};	
										function jstree_handling(){
												$("#jstree2").jstree({"core" : {"data" : {"url" : "index.php?action=treenode"}}});
												$("#jstree2").on("loaded.jstree", function (e, data) {
													if ((storage.isSet("nodeName"))&&(storage.isSet("nodeParentName"))) {
														var nodeParentName = storage.get("nodeParentName");
														var nodeName = storage.get("nodeName");
														var parentelementstring = "#"+nodeParentName;
														var elementstring = "a:contains(\'"+nodeName+"\')";
														var parent = $(parentelementstring+" a");
														var element = $(elementstring);
														parent.click();
														setTimeout(delay_click(element),1500);
													};
												}).jstree();
												$("#jstree2").on("changed.jstree", function (e, data) {
					    								var i, j,n = [], p = [] , m = [];
							    						for(i = 0, j = data.selected.length; i < j; i++) {
							      							n.push(data.instance.get_node(data.selected[i]).text);
							      							p.push(data.instance.get_parent(data.selected[i]));
							      							m.push(data.instance.get_parent(data.selected[i]));
							    						}
	    												if ((p=="#")||(p=="0")) {
	    													$("#tree-node-content").html("<h1 style=text-align:center;color:white;margin-top:80px;>لطفأ یکی از گروه ها را از درخت سمت راست انتخاب کنید.</h1>").fadeIn("300");
	    												}else{
	    													storage.set("nodeParentName" , m.toString());
	    													storage.set("nodeName" , n.toString());
	    													get_node_content("ou="+n.toString());
	    												};
							  						}).jstree();
											};

										function management_panel_handler(){
											$.post("index.php",
	  											{action:"managment"},
	                                                function(data){
	                                                	if (data.managment=="ok") {
	                                                		set_addmaneger_wizard_html();
	                                                        $(".manage-extend").html(data.dashboard_link);
	                                                        $(".manage-extend").append(data.dropdown);
	                                                        $("div.manage_ou").html(data.management_ou_popup);
	                                                        $("div.manage_ou").append(data.management_ou_popup);
	                                                        $("div.modify-popup div.wizard_trigger").html(data.management_manager_wizard_trigger);
	                                                        $("div.manager-permission-list-tining").html(data.manager_permissions_edit);
	                                                        wizard_btn_run();
	                                                        btn_management_handler();
	                                                    };$("p.user_name").text(data.management_name);
	                                         });
										};
										function btn_management_handler(){
											$(".btn-ou").click(function(){
											handle_ou_list();
											var form_ou = $("div.manage_ou");
												showpopup(form_ou);
											});
											$(".btn-managers-pupup-trigger").click(function(){
											set_modify_maneger_content_html();
											var form_managers = $("div.manage_managers_popup");
												showpopup(form_managers);
											});
										};
										function handle_ou_list(){
											$.post("index.php",
	  											{action:"oulist"},
	                                                function(data){
	                                                	if (data.status=="true") {
	                                                		$("#table-bundle").empty();
	                                                         $.each(data.array , function(index , object){
						                                   			frag_ou = temp_ou.replace(/{{dn}}/ig , object.ou)
						                                   							  .replace(/{{name}}/ig , object.name)
						                                   							  .replace(/{{capacity}}/ig , object.capacity);		     
						                                   			$("#table-bundle").append(frag_ou);
						                                        });
														};
														ou_select_handler();
														$("#ou_add").val("");
	                                         });
										};
										function ou_select_handler(){
											$(".ou_select").click(function(){
												var that_ou = $(this);
												$("tr").removeClass("ou_highlight");
												$(".ou_capacity").removeAttr("disabled");
												that_ou.parent().parent().addClass("ou_highlight");
												$(".ou_capacity").val(that_ou.data("capacity"));
											});
											ou_buttons_handle();
											$(".ou_capacity").val("").attr("disabled","disabled");
										};
										function ou_buttons_handle(){
												$(".ou_delete").one("click",function(){
													var that_ou_delete_btn = $(this);
													var dn = $("tr.ou_highlight").data("dn");
													$.post("index.php",
		  											{action:"oudelete",
		  											 	 dn:dn},
		                                                function(data){
		                                                	if (data.status=="false") {
		                                                		var errorpopup = $("div.alertbox");
																showpopup(errorpopup);
															}else{
																var ou_wow_popup = $(".ou_action_success");
		                                                		showpopup(ou_wow_popup);
		                                                		that_ou_delete_btn.parent().parent().remove();
		                                                		$("#jstree2").jstree("refresh");
															}
		                                         });
												});
												$(".ou_add_btn").click(function(){
													var that_ou_add_btn = $(this);
													var send_data = $("#ou_add").val();
													var dn = $("tr.ou_highlight").data("dn");
													$.post("index.php",
		  											{action:"ouadd",
		  											 	 ouname:send_data},
		                                                function(data){
		                                                	if (data.status=="true") {
		                                                		var ou_wow_popup = $(".ou_action_success");
		                                                		showpopup(ou_wow_popup);
		                                                		$("#jstree2").jstree("refresh");
															}else{
																var errorpopup = $("div.alertbox");
																showpopup(errorpopup);
															}
		                                         	});
												});
												$(".ou_capacity_btn").click(function(){
													var capacity_value = $(".ou_capacity").val();
													if(capacity_value){
														$.post("index.php",
															{action:"set_capacity",
															target_ou:$(".ou_highlight").data("dn"),
															new_max:$(".ou_capacity").val()},
															function(data){
																if (data.status=="true") {
		                                                		var ou_wow_popup = $(".ou_action_success");
		                                                		showpopup(ou_wow_popup);
															}else{
																var errorpopup = $("div.alertbox");
																showpopup(errorpopup);
															}
															});
													}
												});
										};
										function set_addmaneger_wizard_html(){
											$.post("index.php",
			  										{action:"wizard_html"},
					  								function(data){
					  									if (data.status=="true") {
					  										$("#wizard").html(data.wizard_html);
					  										step_handling();
						            					};
				        						});
										};
										function set_modify_maneger_content_html(){
											var maneger_popup_tmpl = $.trim($("#content-template-managers-modify").html());
											$("tbody.maneger_list_tbody").empty();
											$.post("index.php",
			  										{action:"maneger_popup_content"},
			  											 function(data){
			  											 	if (data.status=="true") {
			  											 		fragmp=+"";
			  											 		$.each(data.content , function(index , object){
													                fragmp = maneger_popup_tmpl.replace(/{{oudn}}/ig , object.ou_dn).replace(/{{ouname}}/ig , object.ou_name).replace(/{{manegers_list_html}}/ig , object.manegers_list_html);
													                 $("tbody.maneger_list_tbody").append(fragmp);
						  										});
			  											 		$(".manegers_select").click(function(){
																var spesific_ou_dn = $(this).parent().parent().data("oudn");
																var ou_rdn = $(this).parent().parent().children("td:first-child").html();
																set_modify_ou_manegers_content_html(spesific_ou_dn,ou_rdn);
																var form_ou_managers = $("div.manage_ou_managers_popup");
																showpopup(form_ou_managers);
															});
			  											 	}
			  									});
										};
										function set_modify_ou_manegers_content_html(our_ou_dn,our_ou_rdn){
											var ou_maneger_popup_tmpl = $.trim($("#content-template-ou-managers-modify").html());
											$("tbody.manegers_ou_list_tbody").empty();
											$(".ou_name_i").html(our_ou_rdn);
											$.post("index.php",
			  										{action:"ou_manegers_popup_content",
			  										  ou_dn:our_ou_dn},
			  											 function(data){
			  											 	if (data.status=="true") {
			  											 		fragmpq=+"";
			  											 		$.each(data.content , function(index , object){
													                fragmpq = ou_maneger_popup_tmpl.replace(/{{manegerdn}}/ig , object.maneger_dn).replace(/{{manegername}}/ig , object.maneger_name).replace(/{{manegers_permission_html}}/ig , object.manegers_permission_html);
													                 $("tbody.manegers_ou_list_tbody").append(fragmpq);
						  									});
			  												$(".ou_maneger_select").click(function(){
																var maneger_dn = $(this).parent().parent().data("manegerdn");
																var manager_cn = $(this).parent().parent().children("td:first-child").html()
																var attributes_array = ["alisouri"];
																var attributes = $(this).parent().parent().children("td:nth-child(2)").children("p");
																$.each(attributes,function(index,object){
																	attributes_array.push(attributes.eq(index).data("manegerpermission"));
																});
																handle_modify_ou_manegers_content(maneger_dn,manager_cn,our_ou_rdn,our_ou_dn,attributes_array);
																var form_ou_managers = $("div.manage_one_manager_attributes_popup");
																showpopup(form_ou_managers);
															});
			  											 	}
			  									});
										};
										function handle_modify_ou_manegers_content(manager_dn,manager_cn,ou_rdn,ou_dn,attributes_array){
											$(".ou_manager_name").html(manager_cn).data("manegerdn",manager_dn);
											$(".manager_ou_name").html(ou_rdn.substr(3)).data("oudn",ou_dn);
											$("input.attributes_checkbox").removeAttr("checked");
											$.each(attributes_array,function(index,object){
												$("input.attributes_checkbox[name="+object+"]").attr("checked","checked");
											});
											$("input.attributes_checkbox").on("change",function(e){
												$(".admin-save-change-permisson").removeAttr("disabled");
											});
											 $(".admin-save-change-permisson").click(function(){
											 	var manager_permissionseses = [];
											 	$.each($("input.attributes_checkbox:checked").serializeArray(),function(index,object){
												manager_permissionseses[index] = object.name;
												});
											 	var new_permissions = JSON.stringify(manager_permissionseses);
											 	$.post("index.php", 
													 	{action:"change_user_permissions",
												  	destination_ou_dn:$("i.manager_ou_name").data("oudn"),
												  	admin_dn:$("i.ou_manager_name").data("manegerdn"),
													new_permissions:new_permissions} 
											 		,function(data){
											 			console.log("salam");
											 			console.log(data);
											 			if (data.status=="true") {
											 				var change_success_popup = $(".succedens-change-permission");
											 				showpopup(change_success_popup);
											 			}
											 		});
											 });
										};
										function wizard_parts_handling(dnuntilgrandpa){
											var wizard_radio_tmpl = $.trim($("#maneger-group-template").html());
											var wizard_attribute_tmpl = $.trim($("#maneger-attribute-template").html());
											$("#wizard").steps("reset");
											$("div.controls-radio , div.controls-checkbox").empty();
											$.post("index.php",
			  										{action:"wizard_content",
			  								   incomplitedn:dnuntilgrandpa ,
			  											 cn:globalparent.children(":nth-child(4)").text()},
					  								function(data){
					  									if (data.status=="true") {
					  										$("#manager_name").html(data.username).data("dn",data.userdn);
					  										fragrb=+"";
					  										fragcb=+"";
						  									$.each(data.radiobuttons , function(index , radioobject){
													                fragrb = wizard_radio_tmpl.replace(/{{name}}/ig , radioobject.name).replace(/{{dn}}/ig , radioobject.dn);
													                 $("div.controls-radio").append(fragrb);
						  									});
						  									$.each(data.checkboxes , function(index , checkboxobject){
													                fragcb = wizard_attribute_tmpl.replace(/{{name}}/ig , checkboxobject.name)
													                							  .replace(/{{dataname}}/ig , checkboxobject.dataname);
													                 $("div.controls-checkbox").append(fragcb);
						  									});
						            					}
				        						});		
										};
										function step_handling(){
											$("#wizard").steps({
						                        headerTag: "h2",
						                        bodyTag: "section",
						                        transitionEffect: "slideLeft" ,
						                        onStepChanging: function (event, currentIndex, newIndex){
						                        	if (currentIndex==1) {
						                        		if ($("input[name=optionsRadios]:checked").val()==undefined) {
						                        			return false;
						                        		}else{
						                        			return true;
						                        		}
						                        	}else if (currentIndex==2) {
							                        		if ($(".checkbox input:checked").length) {
								                        		$(".manager_group_name ").empty().append($("<p>",{text:$("input[name=optionsRadios]:checked").parent().children("p").text()}));
								                        		$(".manager_attribute_name").empty();
								                        		$.each($(".checkbox input:checked").serializeArray(),function(index,object){
																	$(".manager_attribute_name").append($("<p>",{text:object.name,class:$(".checkbox input:checked").eq(index).data("attrname")}));		                        			
								                        		});
							                        			return true;
							                        		}else{
							                        			return false;
							                        		}
						                        	}
						                        	return true;
						                        } ,
						                        onFinished:function (event, currentIndex) {
						                        	var admin_values = prepare_set_manager_data();
						                        	$.post("index.php",
						                        		{"action":"admin_add",
						                        	  "target_ou":$("input[name=optionsRadios]:checked").val(),
						                           "admin_values":admin_values},function(data){
						                           	   if (data.status=="true") {
						                           	   		succeed_manager_popup();
						                           	   }
						                        	});
						                        }
						                    });
										};
										
										function prepare_set_manager_data(){
											var data = new Object(); 
											var manager_permissionses = new Object();
											$.each($(".checkbox input:checked").serializeArray(),function(index,object){
												manager_permissionses[index] = $(".checkbox input:checked").eq(index).data("attrname");
											});
											data.manager_dn = $("#manager_name").data("dn");
											data.manager_permissions=manager_permissionses;
											data.toJSON = function(key) {
											    var replacement = new Object();
											    for (var val in this)
											    {
											        if (typeof (this[val]) === "string")
											            replacement[val] = this[val];
											        else
											            replacement[val] = this[val]
											    }
											    return replacement;
											};

											var jsonText = JSON.stringify(data);
											return jsonText;
										};
										function delay_click(element){
											setTimeout(function(){element.click();},1500);
										};
										 function input_handler(condition,determiner,value){
                                                 if(condition&&(handle_determiner(determiner,value))){
                                                        $("#"+determiner).parent().parent().addClass("success");
                                                        $(".yes-add-user").removeAttr("disabled");
                                                  }else{
                                                        $("#"+determiner+"-error-message").fadeIn().parent().parent().addClass("error").addClass(determiner+"-error-cause");
                                                        $(".yes-add-user").attr("disabled","");
                                                  };    
                                                 };
                                         function handle_determiner(determiner,value){
                                                if (determiner=="address") {
                                                        return validateEmail(value+"@test.ir");
                                                };
                                                if (determiner=="mobile") {
                                                        return validatePhone(value);
                                                };
                                         };

										 function check_input_unique(arg , argtype){
											var globalinputcondition ;
	                                        var usable_arg = arg;
	                                        if (argtype=="address") {
	                                          usable_arg = arg + "'.$atdomain.'";
	                                                if (globalounode=="ou=tcz.ir") {usable_arg = arg + "@tcz.ir";};
	                                        };
	                                         $.post("index.php",
		  											{action:"checkinputunique",
		  										 		el:usable_arg,
		  											 eltype:argtype},
		                                                function(data){
		                                                         $.each(data , function(index , object){
						                                        if (object.status=="true"){
						                                                input_handler(true,argtype,arg);
						                                                return true;
						                                        }else{
						                                                input_handler(false,argtype,arg);
						                                                return false;
						                                        }
	                               			 		});
	                                          });

										 };
				                        function get_node_content(our_ou){
				  									if ($("#tree-node-form").length) {
				  										$("#tree-node-form").remove();
				  									};
				  									var buttons = $("");
				  									var table = $("<button id=add-user type=button data-ou=\'"+our_ou+"\' class=btn-cust>اضافه کردن کاربر</button><button id=add-user-excel type=button data-ou="+our_ou+" class=btn-cust>ورودی اکسل</button><table id=tree-node-content-table border=1><tr><th>شماره</th><th>شماره موبایل</th><th>آدرس ایمیل</th><th>نام کامل</th><th>عملیات</th></tr></table>");
												$.post("index.php",
				  										{action:"getnodecontent"
				  											,ou:our_ou},
						  								function(data){
								        					frag=+"";
								        					$("div#tree-node-content").hide();
						  									$("div#tree-node-content").html(table);
						  									$.each(data , function(index , object){
							  										globalindex = index;
													                frag = temp.replace(/{{id}}/ig , index)
													                			.replace(/{{mobile}}/ig , object.mobile)
													                			.replace(/{{address}}/ig , object.address)
													                			.replace(/{{name}}/ig , object.name)
													                            .replace(/{{ou}}/ig , object.ou);
													                 $("table#tree-node-content-table").append(frag);
							            							});
							  									$("table#tree-node-content-table , div#tree-node-content").fadeIn("200");
							  									$(".btn-cust").addClass("btn btn-info btn-large");
							        						run();
				        							});
											};
				                        function run(){
				                        	if (globalfunctionvar=="0") {
				                        globalfunctionvar =function(){
				                        	 $("button#add-user-excel").on("click" , function(){
				                        	 	empty_add_user_excel_form();
												var that1 = $(this);
												var ou = that1.data("ou");
														globalounode = ou;
												$("#ouform").val(ou);
												var form00 = $("div.upload-file");
												showpopup(form00);
												});
				                        	 $("button#add-user").on("click" , function(){
				                        	 	empty_add_user_form();
												var that1 = $(this);
												var ou = that1.data("ou");
														globalounode = ou;
												var form0 = $("div.add-user-form");
												showpopup(form0);
												});
										     $("a.delete-btn").on("click" , function(){    
												var that1 = $(this);
												var ou = that1.data("ou");
														globalou = ou;
										                globalthat = that1;
										                globalparent = that1.parent().parent();
												var form = $("div.delete");
												showpopup(form);
												});
										     $("a.modify-btn").on("click" , function(){
												var that1 = $(this);
												var ou = that1.data("ou");
														globalou = ou;
										                globalthat = that1;
										                globalparent = that1.parent().parent();
										                set_user_modify_form_data(ou);
												var form1 = $("div.modify-popup");
												showpopup(form1);
											});
										 };
										};
										execute(globalfunctionvar);
										 };
										 function execute(fn){
										 	fn();
										 };
										 function helper_run(new_helper_class){
										     $("a.new_modify_"+new_helper_class).on("click" , function(){
												var that1 = $(this);
												var ou = that1.data("ou");
														globalou = ou;
										                globalthat = that1;
										                globalparent = that1.parent().parent();
										                set_user_modify_form_data(ou);
												var form1 = $("div.modify-popup");
												showpopup(form1);
											});
										 };
				                        function get_parent_name(id_string){
												var parents = $("#"+id_string);
												var parentsname =  parents.find("a.jstree-anchor").first().text();
										};
				                        function closepopup(){
				                        $("div.custom , div.alertbox , div.empty-alert , div.phone-alert , div.email-alert").fadeOut("400" , function(){
				                            $("div#background , div#background-temp").fadeOut(500);
				                            });
				                        	return true;
				                        };
				                        function showpopup(popup,temp){
				                        	var srch = screen.height;
												var srcw = screen.width;
												Ph = ((srch/2)-(popup.outerHeight()/2))-70;
												Pw = (srcw/2)-(popup.outerWidth()/2);
												if (temp=="temp") {$("div#background-temp").fadeIn("500");}
												else{$("div#background").fadeIn("500");};
													popup.delay(500).animate({
														"top" : Ph ,
														"left" : Pw
													} , 0).css("position" , "fixed").fadeIn(250);
				                        };
				                        function showalertpopup(popup,emptystatus,emailstatus,mobilestatus){
				                        	var srch = screen.height;
												var srcw = screen.width;
												Ph = ((srch/2)-(popup.outerHeight()/2))-70;
												Pw = (srcw/2)-(popup.outerWidth()/2);
													popup.delay(500).animate({
														"top" : Ph ,
														"left" : Pw
													} , 0).css("position" , "fixed").fadeIn(250);
													if (emptystatus) {
														$(".empty-alert").fadeIn(300);
													};
													if (emailstatus) {
														$(".email-alert").fadeIn(350);
													};
													if (mobilestatus) {
														$(".phone-alert").fadeIn(400);
													};
				                        };
				                          function user_add(parentou , name , mobile , email , pass){
			                                var usable_email = email + "'.$atdomain.'";
			                                if (parentou=="ou=tcz.ir") {usable_email = email + "@tcz.ir";};
			                                if (check_add_user_data(name,mobile,usable_email,pass)) {
			                                $.post("index.php",
				                        		{  action : "useradd"
			                                        ,parentou : parentou 
			                                           , name : name 
			                                         , mobile : mobile
			                                          , email : usable_email 
			                                           , pass : pass}
			                                        ,function(data , status){
			                                                $.each(data , function(index , object){
			                                                        if (object.status=="true"){
			                                                                succeed_popup();
			                                                                update_table(object.ou,mobile,usable_email,name);
			                                                        }else{
			                                                                alert("ﺦﻃﺍیی ﺮﺧ ﺩﺍﺪﻫ ﺎﺴﺗ.");
			                                                        };      
			                                                });
			                                        });
			                                };
			                        };

				                        function user_delete(ou){
				                        	$.post("index.php",
				                        		{ action:"userdelete"
				                        			 ,ou:ou}
				                        		,function(data , status){
				                        			$.each(data , function(index , object){
				                        				if (object.status=="true"){
				                        					var yess_popup = $(".delete-success-popup");
				                        					showpopup(yess_popup);
				                        					globalparent.remove();
				                        				}else{
				                        					alert("خطایی رخ داده است.");
				                        				};	
				                        			});
				                        		});
				                        };
				                        function user_modify(ou , input_data){
				                        	var jsondata = JSON.stringify(input_data);
				                        	var namesarray = ["alisouri"];
				                        	$.each(input_data,function(index,object){
				                        		namesarray.push(input_data[index].name);
				                        	});
				                        	
				                        	if(check_modify_data(input_data)){
				                        	$.post("index.php",
				                        		{action:"usermodify",
				                        			 ou:ou,
				                        		 	new_data:jsondata}
				                        		,function(data){
				                        			$.each(data , function(index , object){
				                        				if (object.status=="true"){
				                        					var yess_popup = $(".modify-success-popup");
				                        					showpopup(yess_popup);
				                        					update_user_info(ou,input_data);
				                        				}else{
				                        					alert("خطایی رخ داده است.");
				                        				};
				                        			});
				                        		});
				                        	};
				                        };
				                        function update_user_info(ou , array){
				                        	$.each(array,function(index,object){
				                        		if (array[index].name=="cn") {
				                        		globalparent.children("td:nth-child(4)").text(array[index].value);				                        			
				                        		}else if(array[index].name=="mail"){
				                        		globalparent.children("td:nth-child(3)").text(array[index].value);
				                        		}
				                        	});
				                        };
				                        function update_table(ou , mobile , email , name){
				                        	var new_id = parseInt(globalindex)+1;
				                        	frag_helper = temp_helper.replace(/{{id}}/ig , parseInt(globalindex)+1)
										                			.replace(/{{mobile}}/ig , mobile)
										                			.replace(/{{address}}/ig , email)
										                			.replace(/{{name}}/ig , name)
										                            .replace(/{{ou}}/ig , ou);
										                 $("table#tree-node-content-table").append(frag_helper);
										                 helper_run(new_id);
				                        };
				                        function succeed_popup(){
				                        	var succeed = $("div.succedens");
				                        	showpopup(succeed,"temp");
				                        };
				                        function succeed_manager_popup(){
				                        	var succeed = $("div.succedens_manager");
				                        	showpopup(succeed,"temp");
				                        };
				                        function empty_add_user_form(){
				                        		$(".control-group").removeClass("error").removeClass("success");
				                        		$(".help-inline").hide();
												$("#name").val("");
										     	$("#mobile").val("");
										     	$("#address").val("");
										     	$("#pass").val("");
				                        };
				                        function empty_add_user_excel_form(){
												$("#fileform").val("");
												$(".bar").css("width","0");
												$("#message").html("");
												$("#percent").text("0%");
				                        };
				                        function check_modify_data(dataarray){
				                        	var name = "";
				                        	var nameexist = false;
				                        	var pass = "";
				                        	var passexist = false;
				                        	var address = "";
				                        	var addressexist = false;
				                        	$.each(dataarray,function(index,object){
				                        		if (dataarray[index].name=="cn") {
				                        			nameexist = true;
				                        			name = dataarray[index].value;
				                        		}else if (dataarray[index].name=="userpassword") {
				                        			passexist = true;
				                        			pass = dataarray[index].value;
				                        		}else if (dataarray[index].name=="mail") {
				                        			addressexist = true;
				                        			address = dataarray[index].value;
				                        		}
				                        	});
				                        	var modify_mobile_status = false;
				                        	var emptystatus = false;
				                        	var emailstatus = false;
				                        	var passstatus = false;
				                        	var errorpopup = $("div.alertbox");
				                        	if (((nameexist)&&(name.length==0))||((addressexist)&&(address.length==0))||((passexist)&&(pass.length==0))) {
				                        		emptystatus = true;
				                        	};
				                        	if (!validateEmail(address)) {
				                        		emailstatus = true;
				                        	};
				                        	if (emptystatus||emailstatus||passstatus) {
				                        		showalertpopup(errorpopup,emptystatus,emailstatus,modify_mobile_status);
				                        		return false;
				                        	}else{return true;};
				                        };
				                        function check_add_user_data(name,mobile,address,pass){
				                        	var emptystatus = false;
				                        	var emailstatus = false;
				                        	var mobilestatus = false;
				                        	var passstatus = false;
				                        	var errorpopup = $("div.alertbox");
				                        	if ((name.length=0)||(mobile.length==0)||(address.length==0)||(pass.length==0)) {
				                        		emptystatus = true;
				                        	};
				                        	if (!validatePhone(mobile)) {
				                        		mobilestatus = true;
				                        	};
				                        	if (!validateEmail(address)) {
				                        		emailstatus = true;
				                        	};
				                        	if (emptystatus||mobilestatus||emailstatus||passstatus) {
				                        		showalertpopup(errorpopup,emptystatus,emailstatus,mobilestatus);
				                        		return false;
				                        	}else{return true;};
				                        };
				                        function validatePhone(phone) { 
									    var rev = /^09\d{2}\s*?\d{3}\s*?\d{4}$/;
									    return rev.test(phone);
										};
										function validateEmail(email) { 
									    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
									    return re.test(email);
										};
										});
										</script>
									</div>
									<div id="background"></div>
									<div id="background-temp"></div>
									<div id="background-temp-refresh"></div>
									<div class="hero-unit custom manage_ou">
									<a class="close">&times;</a>
									</div>
									<div class="hero-unit custom manage_one_manager_attributes_popup">
										<a class="close">&times;</a>
										<div class="manager-permission-list-tining">
								        </div>
									</div>
									<div class="hero-unit custom manage_managers_popup">
										<a class="close">&times;</a>
							            <div class="maneger_table_handler">
										<table class="table table-bordered table-striped">
										  <thead>
										      <tr>
										        <th>نام گروه</th>
										        <th>لیست مدیران</th>
										        <th>عملیات</th>
										      </tr>
										    </thead>
										    <tbody class="maneger_list_tbody">
										    </tbody>
										  </table> 
										  </div>
									</div>
									<div class="hero-unit custom manage_ou_managers_popup">
										<a class="close">&times;</a>
										<p>لیست کاربران و سطح دسترسی آنان در گروه:<span class="ou_name_i"></span></p>
							            <div class="maneger_ou_table_handler">
										<table class="table table-bordered table-striped">
										  <thead>
										      <tr>
										        <th>نام مدیر</th>
										        <th>موارد سطوح دسترسی</th>
										        <th>عملیات</th>
										      </tr>
										    </thead>
										    <tbody class="manegers_ou_list_tbody">
										    </tbody>
										  </table> 
										  </div>
									</div>
							        <div class="hero-unit custom manage_manager">
							         	<a class="close">&times;</a>
								        <div id="wizard"></div>
								    </div>
									 <div class="hero-unit custom alertbox">
							            <a class="close-alert">&times;</a>
							            <h3>هشدار!</h3>
							            <div class="alert alert-error empty-alert">
									    <strong>اخطار!</strong> لطفأ هیچ فیلدی را خالی نگذارید.
									    </div>
									    <div class="alert alert-error email-alert">
									    <strong>ایمیل وارد شده نامعتبر است.</strong>لطفأ برای آدرس دهی ایمیل از الگوی phonenumber@yourdomain.ir استفاده نمایید.
									    </div>
									    <div class="alert alert-error phone-alert">
									    <strong>شماره تلفن وارد شده نامعتبر است.</strong>لطفأ شماره تلفنی شروع شونده با الگوی شماره تلفن های اپراتور های داخل کشور وارد مایید.
									    </div>
							            <p>
							            <button class="btn btn-primary no-alert">
							                    بله
							            </button>
							            </p>
							        </div>
									<div class="hero-unit custom customize add-user-form">
				       				     <a class="close">&times;</a>
				            			<h3>اضافه نمودن کاربر:</h3>
							             <p>لطفأ مشخصات کاربر را با دقت وارد کنید و پس از پر کردن تمام فیلدها بر روی دکمه ثبت کلیک کنید.</p> 
				             			 <p>نام کامل:</p> 
				             			 <div class="control-group">
								            <div class="controls">
								              <input type="text" id="name" name="name" class="modify input-xlarge" value="" />
								            </div>
								          </div>
				            			 <p>شماره موبایل:</p> 
				            			 <div class="control-group">
								            <div class="controls">
				            			 <input type="text" id="mobile" name="mobile" class="modify input-xlarge" value="" />
				            			 <span id="mobile-error-message" class="help-inline">شماره موبایل وارد شده در سیستم موجود است.</span>
				            			 	</div>
								          </div>
				            			 <p>شناسه حرفی:</p> 
				            			 <div class="control-group">
								            <div class="controls">
				            			 <input type="text" id="address" name="email" class="modify input-xlarge" value="" />
				            			 <span id="address-error-message" class="help-inline">آدرس ایمیل وارد شده در سیستم موجود است.</span>
				            			 	</div>
								          </div>
				            			 <p>رمز عبور:</p> 
				            			 <div class="control-group">
								            <div class="controls">
				            			 <input type="password" id="pass" name="pass" class="modify input-xlarge" value="" />
				            			 	</div>
								          </div>
				            			 <p>
				                		 <button class="btn btn-primary yes-add-user" type="submit" value="submit">
											ثبت شود
				                		 </button>
				                		 <button class="btn btn-primary no">
				          				  لغو
				                		 </button>
				            			 </p>
				        			</div>
									<div class="hero-unit custom modify-popup">
				       				     <a class="close">&times;</a>
				       				     <div class="modify-write">
					            			 <h3>ویرایش کاربر:</h3>
								             <p>لطفأ تغییرات مد نظر را اعمال نموده و برای ثبت دکمه «ویرایش شود» را کلیک کنید.</p> 
								            <div class="ou_table_handler_modify">
					            			</div>
					            			 <p class="popup-btn-box">
					                		 <button class="btn btn-primary yes-modify" type="submit" value="submit">
												ویرایش شود
					                		 </button>
					                		 <button class="btn btn-primary no">
					          				  لغو
					                		 </button>
					            			 </p>
				            			 </div>
				            			 <div class="modify-left">
					            			 <h3>حذف کاربر:</h3>
					            			 <p>لطفأ برای حدف کاربر روی دکمه حدف شود کلیک بفرمایید.</p>
					            			 <p class="popup-btn-box">
					                		 <button class="btn btn-primary yes-delete" type="submit" value="submit">
												حذف کاربر
					                		 </button>
					                		 <button class="btn btn-primary no">
					          				  لغو
					                		 </button>
					            			 </p>
					            			 <div class="wizard_trigger">
					            			 </div>
				            			 </div>
				        			</div>
				        			 <div class="hero-unit custom delete delebaba">
							            <a class="close">&times;</a>
							            <h3>اخطار!</h3>
							            <p>آیا برای حذف این کاربر مطمئن هستید؟</p> 
							            <p class="popup-btn-box">
							            <button class="btn btn-primary yes-delete" type="submit" value="submit">
							                    بلی حذف شود
							            </button>
							            <button class="btn btn-primary no">
							                    لغو
							            </button>
							            </p>
							        </div>
							        <div class="hero-unit custom succedens">
							            <a class="close">&times;</a>
							            <h3>پیام:</h3>
							            <p>رکورد شما با موفقیت ثبت شد.</p> 
							            <p class="popup-btn-box">
							            <button class="btn btn-primary no-temp">
							                    بله
							            </button>
							            </p>
							        </div>
							        <div class="hero-unit custom succedens_manager">
							            <a class="close">&times;</a>
							            <h3>پیام:</h3>
							            <p>رکورد شما با موفقیت ثبت شد.</p> 
							            <p>اکنون کاربر مورد نظر دارای قابلیت مدیریت در سیستم است.</p> 
							            <p>با اضافه کردن قابیل مدیریت به کاربر مورد نظر امکان ورود وی به سیستم با شناسه حرفی سیستم ایمیل (یا شماره موبایل وی) به عنوان نام کاربری و همچنین گذر واژه ایمیل وی به عنوان گذر واژه این سیستم به وجود می آید.</p> 
							            <p class="popup-btn-box">
							            <button class="btn btn-primary no-temp">
							                    بله
							            </button>
							            </p>
							        </div>
							        <div class="hero-unit custom ou_action_success">
							            <a class="close">&times;</a>
							            <h3>پیام:</h3>
							            <p>ویرایش مورد نظر روی گروه مورد نظر انجام گردید.</p> 
							            <p class="popup-btn-box">
							            <button class="btn btn-primary no-temp">
							                    بله
							            </button>
							            </p>
							        </div>
							        <div class="hero-unit custom modify-success-popup">
							            <a class="close">&times;</a>
							            <h3>پیام:</h3>
							            <p>ویرایش مورد نظر روی کاربر مورد نظر انجام گردید.</p> 
							            <p class="popup-btn-box">
							            <button class="btn btn-primary no-temp">
							                    بله
							            </button>
							            </p>
							        </div>
							        <div class="hero-unit custom delete-success-popup">
							            <a class="close">&times;</a>
							            <h3>پیام:</h3>
							            <p>اطلاعات کاربر مورد نظر با موفقیت حدف گردید.</p> 
							            <p class="popup-btn-box">
							            <button class="btn btn-primary no-temp">
							                    بله
							            </button>
							            </p>
							        </div>
							        <div class="hero-unit custom succedens-change-permission">
							            <a class="close">&times;</a>
							            <h3>پیام:</h3>
							            <p>رکورد شما با موفقیت ثبت شد.</p> 
							            <p>سطح دسترسی مورد نظر به مدیر مورد نظر در گروه مورد نظر اعمال شد.</p> 
							            <p>کاربر مورد نظر در گروه مورد نظر دارای قابلیت ویرایش و خواندن اطلاعات مشخصه های انتخاب شده توسط شماست.</p> 
							            <p class="popup-btn-box">
							            <button class="btn btn-primary no-temp">
							                    بله
							            </button>
							            </p>
							        </div>
							         <div class="hero-unit custom succedens-refresh">
							            <h3>پیام:</h3>
							            <p>رکورد شما با موفقیت ثبت شد.</p> 
							            <p class="popup-btn-box">
							            <button class="btn btn-primary no-temp no-temp-refresh">
							                    بله
							            </button>
							            </p>
							        </div>
							        <div class="hero-unit custom upload-file">
				       				    <a class="close">&times;</a>
				            			<h3>اضافه نمودن کاربر با استفاده از فایل اکسل:</h3>
				            			<div class="alert-cust alert-info">
									    <strong>توجه!</strong> لطفأ فایل بارگذاری شده حتمأ هز نوع اکسل و با پسوند xls باشد. و حتمأ کاربر تکراری وجود نداشته باشد.
									    </div>
				             			<p>آپلود فایل:</p> 
				             			<form id="myForm" action="index.php?action=xls" method="post" enctype="multipart/form-data">
				            			<input id="fileform" type="file" name="myfile" class=""/>
				            			<input id="ouform" type="text" name="myou" style="display:none;" class=""/>
				            			<div id="progress" class="progress progress-striped active">
										<div id="bar" class="bar"></div>
										<div id="percent">0%</div >
										</div>
										<br/>						    
										<div id="message"></div>
				                		<button class="btn btn-primary yes-upload-file" type="submit" value="submit">
											ارسال شود
				                		</button>
				                		<button type="button" class="btn btn-primary no">
				          				  لغو
				                		</button>
				                		</form>
				        			</div>

					<script src="./views/assets/jquery-1.10.2.min.js"></script>
					<script src="./views/assets/jquery.tmpl.min.js"></script>
					<script src="./views/assets/jquery.address-1.6.js"></script>
					<script src="./views/assets/vakata.js"></script>
					<script src="./views/assets/dist/jstree.min.js"></script>
					<script src="./views/assets/docs.js"></script>
					<script src="./views/js/jquery.form.js"></script>
					<script src="./views/js/jquery.storageapi.min.js"></script>
					<script src="./views/js/modernizr-2.6.2.min.js"></script>
			        <script src="./views/js/jquery.cookie-1.3.1.js"></script>
			        <script src="./views/js/jquery.steps.js"></script>
			        <script src="./views/js/dropdown.js"></script>
					<script>
					function succeed_popup(){
                        	var succeed = $("div.succedens-refresh");
                        	showpopup(succeed,"temp");
                        }
						 function update_table(ou , name  , email , mobile){
							var globalindex=parseInt($("table#tree-node-content-table tr:last-child td:first-child").text());
                        	frag = temp_helper.replace(/{{id}}/ig , globalindex+1)
						                			.replace(/{{mobile}}/ig , mobile)
						                			.replace(/{{address}}/ig , email)
						                			.replace(/{{name}}/ig , name)
						                            .replace(/{{ou}}/ig , ou);
						                 $("table#tree-node-content-table").append(frag);
						                 globalindex++;
                        };
						 function showpopup(popup,temp){
                        	var srch = screen.height;
								var srcw = screen.width;
								Ph = ((srch/2)-(popup.outerHeight()/2))-70;
								Pw = (srcw/2)-(popup.outerWidth()/2);
								$("div#background-temp-refresh").fadeIn("500");
									popup.delay(500).animate({
										"top" : Ph ,
										"left" : Pw
									} , 0).css("position" , "fixed").fadeIn(250);
                        }

						$(document).ready(function()
						{

							var options = { 
						    beforeSend: function() 
						    {
						    	$("#progress").show();
						    	$("#bar").width("%");
						    	$("#message").html("");
								$("#percent").html("0%");
						    },
						    uploadProgress: function(event, position, total, percentComplete) 
						    {
						    	$("#bar").width(percentComplete+"%");
						    	$("#percent").html(percentComplete+"%");
						    },
						    success: function() 
						    {
						        $("#bar").width("100%");
						    	$("#percent").html("100%");
						    },
							complete: function(response) 
							{
								$.each(response.responseJSON , function(index , object){
									$("#message").html("<font color=green>"+object.responsestatustext+"</font>");
									$.each(object.updatetableinfo , function(i,obj){
									update_table(obj.ou,obj.name,obj.email,obj.pass);
									});
								succeed_popup();
								});
							},
							error: function()
							{
								$("#message").html("<font color=red> ERROR: unable to upload files</font>");
							}
						     
						}; 

						     $("#myForm").ajaxForm(options);

						});

				</script>
					<script>$.each($q,function(i,f){$(f)});$q=null;</script>
				</body>
				</html>
		';
	}

	public static function echodashboardpage($user_ou){
		if($user_ou=="ou=*"){

		$ldap_info = ldapinfo::get_ldap_info();

		echo '

<!-- AUI Documentation -->
<!DOCTYPE html>
    <html>
    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Monitoring</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

        <!-- Favicons -->

        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="views/assets/images/icons/apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="views/assets/images/icons/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="views/assets/images/icons/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="assets/images/icons/apple-touch-icon-57-precomposed.png">
        <link rel="stylesheet" type="text/css" href="views/assets/css/rtl.css">

        <!--[if lt IE 9]>
          <script src="assets/js/minified/core/html5shiv.min.js"></script>
          <script src="assets/js/minified/core/respond.min.js"></script>
        <![endif]-->

        <!-- Fides Admin CSS Core -->

        <link rel="stylesheet" type="text/css" href="views/assets/css/minified/aui-production.min.css">

        <!-- Theme UI -->

        <link id="layout-theme" rel="stylesheet" type="text/css" href="views/assets/themes/minified/fides/color-schemes/dark-blue.min.css">

        <!-- Fides Admin Responsive -->

        <link rel="stylesheet" type="text/css" href="views/assets/themes/minified/fides/common.min.css">
        <link rel="stylesheet" type="text/css" href="views/assets/themes/minified/fides/responsive.min.css">

        <!-- Fides Admin JS -->
        <script type="text/javascript" src="views/assets/js/jquery-1.11.1.js"></script>
        <script type="text/javascript" src="views/assets/js/minified/aui-production.min.js"></script>

        <script>
            jQuery(window).load(
                function(){

                    var wait_loading = window.setTimeout( function(){
                      $("#loading").slideUp("fast");
                      jQuery("body").css("overflow","auto");
                    },1000
                    );

                });
        </script>

        <script type="text/javascript" src="views/assets/syntax-highlighter/scripts/shCore.js"></script>
        <script type="text/javascript" src="views/assets/syntax-highlighter/scripts/shBrushPhp.js"></script>
        <link type="text/css" rel="stylesheet" href="views/assets/syntax-highlighter/styles/shCoreDefault.css">
        <script type="text/javascript">SyntaxHighlighter.all();</script>

    </head>
    <body>

        <div id="loading" class="ui-front loader ui-widget-overlay bg-white opacity-100">
            <img src="views/assets/images/loader-dark.gif" alt="">
        </div>

        <div id="page-wrapper">
            <div id="page-header" class="clearfix">
                <div id="header-logo" class="dashboard-system" ><a class="mainpage-btn" href="'.$ldap_info["mainpageurl"].'">سامانه مدیریت ایمیل</a></div>
                <div id="header-logo" class="exit-system"><a class="exit-btn" href="'.$ldap_info["mainpageurl"].'?logout'.'">خروج</a></div>

            </div><!-- #page-header -->

            <div id="page-sidebar" class="scrollable-content">

                <div id="sidebar-menu">
                    <ul>
                        <li>
                            <a href="javascript:;" title="آمار کلی" data-page="1">
                                <i class="glyph-icon icon-dashboard"></i>
                                امار کلی
                            </a>
                        </li>
                        <li>
                            <a href="javascript:;" title="ظرفیت ها" data-page="2">
                                <i class="glyph-icon icon-dashboard"></i>
                                ظرفیت ها 
                            </a>
                        </li>
                        <li>
                            <a href="javascript:;" title="آمار کاربری" data-page="3">
                                <i class="glyph-icon icon-dashboard"></i>
                                آمار کاربری
                            </a>
                        </li>                       
                            
                    </ul>
                    <div class="divider mrg5T mobile-hidden"></div>
                </div>

            </div><!-- #page-sidebar -->
            </div><!-- #page-main -->
        <!-- #page-wrapper -->
        <div id="page-content-wrapper" class="page-1 showing">
                <div id="page-title">
                <h3>آمار کلی</h3>
                    <div class="example-code">
                        <div class="row mrg20B">

                            <div class="col-lg-3">

                                <a href="javascript:;" class="tile-button btn bg-blue-alt" title="">
                                    <div class="tile-header">
                                        تعداد کل اکانتها 
                                    </div>
                                    <div class="tile-content-wrapper">
                                        <i class="glyph-icon icon-dashboard"></i>
                                        <div class="tile-content account-number">
                                      
                                        </div>
                                        <small>
                                            <i class="glyph-icon icon-caret-up"></i>
                                        </small>
                                    </div>
                                                                 </a>

                            </div>

                            <div class="col-lg-3">

                                <a href="javascript:;" class="tile-button btn bg-black" title="">
                                    <div class="tile-header">
                                        تعداد کل گروه ها 
                                    </div>
                                    <div class="tile-content-wrapper">
                                        <i class="glyph-icon icon-dashboard"></i>
                                        <div class="tile-content group-number">
                                            
                                       
                                        </div>
                                        <small>
                                            <i class="glyph-icon icon-caret-up"></i>
                                            
                                        </small>
                                    </div>
                                                                 </a>

                            </div>


                            <div class="col-lg-3">

                                <a href="javascript:;" class="tile-button btn bg-gray-alt" title="">
                                    <div class="tile-header">
                                        تعداد ادمین های محلی
                                    </div>
                                    <div class="tile-content-wrapper">
                                        <i class="glyph-icon icon-dashboard"></i>
                                        <div class="tile-content admin-number">
                                             
                                           
                                        </div>
                                        <small>
                                            <i class="glyph-icon icon-caret-up"></i>
                                            
                                        </small>
                                    </div>
                                                                 </a>

                            </div>

                        </div>
                    
            </div>
            </div>
           		
            </div>
            
                <div id="page-content-wrapper" class="page-2">
                <div id="page-title">
                <h3>ظرفیت حجم</h3>
                    <div class="example-code">
                        <div class="row mrg20B">
                            <div class="col-lg-3">

                                <a href="javascript:;" class="tile-button btn bg-black" title="">
                                    <div class="tile-header">
                                		عدد ظرفیت کل فضا
                                    </div>
                                    <div class="tile-content-wrapper">
                                        <i class="glyph-icon icon-dashboard"></i>
                                        <div class="tile-content entire-Capacity">
                                        </div>
                                        <small>
                                            <i class="glyph-icon icon-caret-up"></i>
                                            
                                        </small>
                                    </div>
                                                                 </a>

                            </div>
                        </div>
                        <div class="row mrg20B">
                        	<div class="col-lg-3">
                        		<a href="javascript:;" class="tile-button btn bg-blue-alt" title="">
                                    <div class="tile-header">
                                     	  عدد ظرفیت آزاد حجم
                                    </div>
                                    <div class="tile-content-wrapper">
                                        <i class="glyph-icon icon-dashboard"></i>
                                        <div class="tile-content Availabel-capacity-num">
                                        </div>
                                        <small>
                                            <i class="glyph-icon icon-caret-up"></i>
                                            
                                        </small>
                                    </div>
                                </a>

                        	</div>
                        	<div class="col-lg-3">
                        		<a href="javascript:;" class="tile-button btn bg-blue-alt" title="">
                                    <div class="tile-header">
                                         درصد ظرفیت آزاد از کل حجم
                                    </div>
                                    <div class="tile-content-wrapper">
                                        <i class="glyph-icon icon-dashboard"></i>
                                        <div class="tile-content Availabel-capacity-percent">
                                        	400
                                        </div>
                                        <small>
                                            <i class="glyph-icon icon-caret-up"></i>
                                            
                                        </small>
                                    </div>
                                </a>

                        	</div>
                        	<div class="col-lg-3">
								<div class="content-box pad10A">
						        <div id="free-percent" class="medium-gauge"></div>
						     </div>
	
                        	</div>
                        </div>
                    	<div class="row mrg20B">
                    		                        	<div class="col-lg-3">
                        		<a href="javascript:;" class="tile-button btn bg-gray-alt" title="">
                                    <div class="tile-header">                       	
                                    	          عدد ظرفیت استفاده شده حجم
                      				</div>
                                    <div class="tile-content-wrapper">
                                        <i class="glyph-icon icon-dashboard"></i>
                                        <div class="tile-content used-capacity-num">
                                        </div>
                                        <small>
                                            <i class="glyph-icon icon-caret-up"></i>
                                            
                                        </small>
                                    </div>
                                </a>

                        	</div>
                        	<div class="col-lg-3">
                        		<a href="javascript:;" class="tile-button btn bg-gray-alt" title="">
                                    <div class="tile-header">
                                       درصد استفاده شده از کل ظرفیت حجم
                                    </div>
                                    <div class="tile-content-wrapper">
                                        <i class="glyph-icon icon-dashboard"></i>
                                        <div class="tile-content used-capacity-percent">
                                        </div>
                                        <small>
                                            <i class="glyph-icon icon-caret-up"></i>
                                            
                                        </small>
                                    </div>
                                </a>

                        	</div>
                        	<div class="col-lg-3">	
                        		<div class="content-box pad10A">
						        <div id="Used-percent" class="medium-gauge"></div>
						     </div>
	

                        	</div>
                    	</div>
                    <div class="Info-Box"></div>
            </div>
            
            <div id="page-title">
                <h3>ظرفیت کاربری </h3>
                    <div class="example-code">
                        <div class="row mrg20B">
                            <div class="col-lg-3">

                                <a href="javascript:;" class="tile-button btn bg-black" title="">
                                    <div class="tile-header">
                                        عدد ظرفیت کل کاربران
                                    </div>
                                    <div class="tile-content-wrapper">
                                        <i class="glyph-icon icon-dashboard"></i>
                                        <div class="tile-content capacity-number-MB">
                                        </div>
                                        <small>
                                            <i class="glyph-icon icon-caret-up"></i>
                                            
                                        </small>
                                    </div>
                                                                 </a>

                            </div>
                        </div>
                        <div class="row mrg20B">
                        	<div class="col-lg-3">
                        		<a href="javascript:;" class="tile-button btn bg-blue-alt" title="">
                                    <div class="tile-header">
                                        عدد ظرفیت آزاد کاربران
                                    </div>
                                    <div class="tile-content-wrapper">
                                        <i class="glyph-icon icon-dashboard"></i>
                                        <div class="tile-content capacity-number-num">
                                        </div>
                                        <small>
                                            <i class="glyph-icon icon-caret-up"></i>
                                            
                                        </small>
                                    </div>
                                </a>

                        	</div>
                        	<div class="col-lg-3">
                        		<a href="javascript:;" class="tile-button btn bg-blue-alt" title="">
                                    <div class="tile-header">
                                        درصد ظرفیت آزاد کاربران
                                    </div>
                                    <div class="tile-content-wrapper">
                                        <i class="glyph-icon icon-dashboard"></i>
                                        <div class="tile-content capacity-number-percent">
                                        </div>
                                        <small>
                                            <i class="glyph-icon icon-caret-up"></i>
                                            
                                        </small>
                                    </div>
                                </a>

                        	</div>
                        	<div class="col-lg-3">
								<div class="content-box pad10A">
						        <div id="free-Capacity" class="medium-gauge"></div>
						     </div>
	

                        	</div>
                        </div>
                    	<div class="row mrg20B">
                    		                        	<div class="col-lg-3">
                        		<a href="javascript:;" class="tile-button btn bg-gray-alt" title="">
                                    <div class="tile-header">
                                 		عدد ظرفیت استفاده شده تعداد کاربران 
                      				</div>
                                    <div class="tile-content-wrapper">
                                        <i class="glyph-icon icon-dashboard"></i>
                                        <div class="tile-content free-user-capacity-vol">
                                        </div>
                                        <small>
                                            <i class="glyph-icon icon-caret-up"></i>
                                            
                                        </small>
                                    </div>
                                </a>

                        	</div>
                        	<div class="col-lg-3">
                        		<a href="javascript:;" class="tile-button btn bg-gray-alt" title="">
                                    <div class="tile-header">
                                        درصد ظرفیت استفاده شده تعداد کاربران
                                    </div>
                                    <div class="tile-content-wrapper">
                                        <i class="glyph-icon icon-dashboard"></i>
                                        <div class="tile-content free-user-capacity-percent">
                                        </div>
                                        <small>
                                            <i class="glyph-icon icon-caret-up"></i>
                                            
                                        </small>
                                    </div>
                                </a>

                        	</div>
                        	<div class="col-lg-3">
                        		<div class="content-box pad10A">
						        <div id="Vol-free-capacity" class="medium-gauge"></div>
						     </div>
	

                        	</div>
                    	</div>
                    <div class="Info-Box"></div>
            </div>
            </div>
            </div>
            </div>
                    <div id="page-content-wrapper" class="page-3">
                <div id="page-title">
                <h3>آمار کاربران</h3>
                    <div class="example-code">
                        <div class="row mrg20B">

                            <div class="col-lg-3" data-col="1">

                                <a href="javascript:;" class="tile-button btn bg-black" title="">
                                    <div class="tile-header">
                                        تعداد اکانت های تمام شده 
                                    </div>
                                    <div class="tile-content-wrapper">
                                        <i class="glyph-icon icon-dashboard"></i>
                                        <div class="tile-content expire-account">
                                             0
                                        </div>
                                        <small>
                                            <i class="glyph-icon icon-caret-up"></i>
                                            
                                        </small>
                                    </div>
                                                                 </a>

                            </div>

                            <div class="col-lg-3" data-col="2">

                                <a href="javascript:;" class="tile-button btn bg-gray-alt" title="">
                                    <div class="tile-header">
                                        تعداد اکانتهای غیر فعال
                                    </div>
                                    <div class="tile-content-wrapper">
                                        <i class="glyph-icon icon-dashboard"></i>
                                        <div class="tile-content unused-account">
                                            0
                                        </div>
                                        <small>
                                            <i class="glyph-icon icon-caret-up"></i>
                                            
                                        </small>
                                    </div>
                                                                 </a>

                            </div>


                            <div class="col-lg-3" data-col="3">

                                <a href="javascript:;" class="tile-button btn bg-blue-alt" title="">
                                    <div class="tile-header">
                                        تعداد اکانتهای لاگین شده
                                    </div>
                                    <div class="tile-content-wrapper">
                                        <i class="glyph-icon icon-dashboard"></i>
                                        <div class="tile-content loged-in-account">
                                            0
                                        </div>
                                        <small>
                                            <i class="glyph-icon icon-caret-up"></i>
                                            
                                        </small>
                                    </div>
                                                                 </a>

                            </div>

                        </div>
            </div>
            </div>

            </div>





<!-- =============================== -->
<script type="text/javascript" src="views/assets/js/justgage.1.0.1.js"></script>
<script type="text/javascript" src="views/assets/js/raphael.2.1.0.min.js"></script>
<script type="text/javascript">
var  free_percent, Used_percent , free_Capacity , Vol_free_capacity ;
    $(document).ready(function(){
    	getStatiction();
        $("#sidebar-menu ul li a").on("click" , function(){
            var $this = $(this);
            var page = $this.data("page");
            $("div.page-" + page).siblings("#page-content-wrapper").removeClass("showing");
            $("div.page-" + page).addClass("showing");
            var timer = 0;
            if(page == 1){
            	console.log("1 is runnig");
            	clearInterval(timer);
            	getStatiction();
            	timer = setInterval(getStatiction , 10000);
            } else if(page == 2 ){
            	console.log("2 is runnig");
            	clearInterval(timer);
            	getCapacity();
            	timer = window.setInterval(getCapacity,10000);
             } else if(page == 3){
            	 console.log("3 is runnig");
            	 clearInterval(timer);
            	 getStatus();
            	 timer = window.setInterval(getStatus,20000)
             }
        });
        function getStatiction() {
            $.post("index.php" , {"action" : "numberofallaccounts"} , function(data) {
                if (data.status == "true") {
                    $(".account-number").text(data.content);
                };
            });
            $.post("index.php" , {"action" : "numberofallgroups"} , function(data){
                if (data.status == "true") {
                    $(".group-number").text(data.content);
                };
            });
            $.post("index.php" , {"action" : "numberoflocaladmins"} , function(data){
                if (data.status == "true") {
                    $(".admin-number").text(data.content);
                };
            })
        }
	 function getCapacity(){
	 	$.post("index.php" , {"action" : "allsystemquota"} , function(data){
	 		if(data.status == "true") {
	 			$(".entire-Capacity").text(data.content);
	 		}
	 	});
	 	$.post("index.php" , {"action" : "systemfreequota"} , function(data){
	 		if(data.status == "true"){
	 			$(".Availabel-capacity-num").text(data.content.number);
	 			$(".Availabel-capacity-percent").text(data.content.percent);
	 			free_percent.refresh(data.content.percent);
	 		}
	 	});
	 	$.post("index.php" , {"action" : "systemusedquota"} , function(data){
	 		if(data.status == "true"){
	 			$(".used-capacity-num").text(data.content.number);
	 			$(".used-capacity-percent").text(data.content.percent);
	 			Used_percent.refresh(data.content.percent);
	 		}
	 	});
	 	$.post("index.php" , {"action" : "allusercapacity"} , function(data){
	 		if(data.status == "true") {
	 			$(".capacity-number-MB").text(data.content);
	 		}
	 	});
	 	$.post("index.php" , {"action" : "systemfreeusercapacity"} , function(data){
	 		if(data.status == "true"){
	 			$(".capacity-number-num").text(data.content.number);
	 			$(".capacity-number-percent").text(data.content.percent);
	 			free_Capacity.refresh(data.content.percent);
	 		}
	 	})
	 	$.post("index.php" , {"action" : "systemusedusercapacity"} , function(data){
	 		if(data.status == "true"){
	 			$(".free-user-capacity-vol").text(data.content.number);
	 			$(".free-user-capacity-percent").text(data.content.percent);
	 			Vol_free_capacity.refresh(data.content.percent);
	 		}
	 	})


	 	
	 }

	 function getStatus(){
		$.post("index.php" , {"action" :"numberoffinishedaccounts"} , function(data){
			if(data.status == "true"){
				$(".expire-account").text(data.content);
			}
		})
		$.post("index.php" , {"action" :"numberofdisableaccounts"} , function(data){
			if(data.status == "true"){
				$(".unused-account").text(data.content);
			}
		})
		$.post("index.php" , {"action" :"numberofloginaccounts"} , function(data){
			if(data.status == "true"){
				$(".loged-in-account").text(data.content);
			}
		})
	}

	 $(function() {

//      
    window.onload = function(){
     
      free_percent = new JustGage({
        id: "free-percent", 
        value: 30, 
        min: 0,
        max: 100,
        title: "نمودار ظرفیت آزاد حجم",
        label: "مگابایت"
      });
      Used_percent = new JustGage({
        id: "Used-percent", 
        value: 30, 
        min: 0,
        max: 100,
        title: "نمودار ظرفیت استفاده شده حجم" ,
        label: "مگابایت"
      });
      free_Capacity = new JustGage({
        id: "free-Capacity", 
        value: 30, 
        min: 0,
        max: 100,
        title: "نمودار ظرفیت آزاد کاربران",
        label: ""
      });
      Vol_free_capacity  = new JustGage({
        id: "Vol-free-capacity", 
        value: 30, 
        min: 0,
        max: 100,
        title: "نمودار ظرفیت استفاده شده کاربران ",
        label: ""
      });
    };
  });

    })
</script>





</script>
    </body>
</html>



';
		}else{
			echo "you have not permission to access this page.";
		};
	}
		
}
?>