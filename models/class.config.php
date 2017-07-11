<?php
namespace LP\LDAP\AUTH\CONNECT\XML;
//LP\LDAP\AUTH\CONNECT\XML\ldaphelper

ini_set('display_errors',1); 
 error_reporting(E_ALL);

	class ldaphelper{

			public static function getLdapValues(){
				$xmlstr = <<<XML
<?xml version="1.0" encoding="UTF-8" ?>
			<ldap>
			<ldapdomain>fci.co.ir</ldapdomain>
    		<ldapaddress>127.0.0.1</ldapaddress>
    		<ldapport>389</ldapport>
    		<ldapbindrdn>cn=admin,dc=elenoon,dc=ir</ldapbindrdn> 
    		<ldapbindpass>abas</ldapbindpass> 
    		<projecturl>localhost</projecturl>
    		<mainserverrdn>dc=elenoon,dc=ir</mainserverrdn>
    		<mainpageurl>http://localhost/ldap-0.0.2/index.php</mainpageurl>
    		<customadmindn>cn=customadmin</customadmindn>
    		<customadminrdn>cn=customadmin,dc=elenoon,dc=ir</customadminrdn>
    		<customadminusername>customadmin</customadminusername>
    		<customadminpass>abas?1371</customadminpass>
    		<bindadmindn>cn=admin</bindadmindn>
    		<node1rdn>ou=node1,dc=elenoon,dc=ir</node1rdn>
    		<node1usablerdn>,ou=node1,dc=elenoon,dc=ir</node1usablerdn>
    		<zarafauserserver>192.168.0.22</zarafauserserver>
    		<defaultoucapacity>100</defaultoucapacity>
    		<allusercapacity>10000</allusercapacity>
    		<allsystemquota>200000</allsystemquota>
    		<oustar>ou=*</oustar>
    		<zcpmachineuser>root</zcpmachineuser>
    		<zcpmachineserver>127.0.0.1</zcpmachineserver>
    		<zcpmachineport>22</zcpmachineport>
    		<zcpmachinepassword>abas</zcpmachinepassword>
    		<apachemachineuser>root</apachemachineuser>
    		<apachemachineserver>127.0.0.1</apachemachineserver>
    		<apachemachineport>22</apachemachineport>
    		<apachemachinepassword>abas</apachemachinepassword>
			</ldap>
XML;
				return $xmlstr;
			}


			public static function getgidvalues(){
				$xmlstr = <<<XML
<?xml version="1.0" encoding="UTF-8" ?>
			<gidnumber>
				<fci>100002</fci>
				<kerman>100003</kerman>
				<khorasan_jonobi>100004</khorasan_jonobi>
				<khorasan_razavi>100005</khorasan_razavi>
				<khorasan_shomali>100006</khorasan_shomali>
				<support>100007</support>
				<tci>100008</tci>
				<tehran>100009</tehran>
				<tcz>100010</tcz>
				<yazd>100011</yazd>
				<merto>100012</merto>
				<ghazvin>100013</ghazvin>
				<xls>1010</xls>
			</gidnumber>
XML;
				return $xmlstr;
			}

	}






	?>
