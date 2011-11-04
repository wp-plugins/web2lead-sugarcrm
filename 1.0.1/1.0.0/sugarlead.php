<?php
//	this page takes input from the contact form and generates a SugarCRM Lead record
$url = get_option('web2lead_sugarcrm_url');
$user = get_option('web2lead_sugarcrm_user');
$pwd = get_option('web2lead_sugarcrm_pwd');
$soapclient = new SoapClient(null, array(
	'location'=>$url,
	'uri'=>'http://www.sugarcrm.com/sugarcrm',
	'soap_version'=>'SOAP_1_1',
	'trace'=>1,
	'exceptions'=>0
	));

//	login to SugarCRM
$user_auth = array(
	'user_name' => $user,
	'password' => md5($pwd),
	'version'=>$soapclient->get_server_version()
	);
$app = 'web2lead';

$login = $soapclient->login($user_auth,$app);
$session_id = $login->id;

//	get user id
$user_id = $soapclient->get_user_id($session_id);

//	get form data
$name = $_POST["txtName"];
$company = $_POST["txtCompany"];
$phone = $_POST["txtPhone"];
$email = $_POST["txtEmail"];
$comments = $_POST["txtComments"];

//	try to parse name field into first name and last name (everything before the first space = first name. all other = last name).
$sp = stripos($name, ' ');
$first_name = substr($name,0,$sp);
$last_name = substr($name, $sp+1,strlen($name));

// The following lines will use the set_entry SOAP call to add a Lead
$data = array(
	array('name'=>'first_name','value'=>$first_name),
	array('name'=>'last_name','value'=>$last_name),
	array('name'=>'status', 'value'=>'New'),
	array('name'=>'phone_work', 'value'=> $phone),
	array('name'=>'email1', 'value'=>$email),
	array('name'=>'account_name','value'=>$company),
	array('name'=>'lead_source','value'=>'Web Site'),
	array('name'=>'description','value'=>$comments),
	array('name'=>'assigned_user_id', 'value'=>$user_id)
);

//	execute the create
$result = $soapclient->set_entry($session_id, 'Leads', $data);

//	log out of SugarCRM
$logout_results = $soapclient->logout($session_id);
?>