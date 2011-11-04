<?php
class SugarLead {

	//	variables
	private $url;
	private $user;
	private $pwd;
	private $notify_email;
	private $app;
	private $soap_client;
	private $session_id;
	private $user_id;
	private $send_email;
	
	//	constructor - set our variables
	function __construct() {
		$this->url = get_option('web2lead_sugarcrm_url');
		$this->user = get_option('web2lead_sugarcrm_user');
		$this->pwd = get_option('web2lead_sugarcrm_pwd');
		$this->notify_email = get_option("admin_email");	
		$this->app = 'web2lead';
		$this->soap_client = new SoapClient(null, array(
				'location'=>$this->url,
				'uri'=>'http://www.sugarcrm.com/sugarcrm',
				'soap_version'=>'SOAP_1_1',
				'trace'=>1,
				'exceptions'=>1
				));
		$this->send_email = true;
	}

	//	log into SugarCRM
	function Login() {
		try {
			$user_auth = array(
				'user_name' => $this->user,
				'password' => md5($this->pwd),
				'version'=>$this->soap_client->get_server_version()
				);
			$login = $this->soap_client->login($user_auth,$this->app);
			$this->session_id = $login->id;

			//	get user id
			$this->user_id = $this->soap_client->get_user_id($session_id);
			return true;
		}
		catch(Exception $e) {
			throw new Exception('Error logging into SugarCRM');
		}
	}
	
	//	log out of SugarCRM
	function Logout() {
		$logout_results = $this->soap_client->logout($this->session_id);	
	}
	
	//	wrapper for set_entry call
	function SetEntry($module, $data) {
		return $this->soap_client->set_entry($this->session_id, $module, $data);
	}
	
	//	create lead record
	public function CreateLead() {
		try{
			if($this->Login()) {
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
				
				//	create data array
				$data = array(
					array('name'=>'first_name','value'=>$first_name),
					array('name'=>'last_name','value'=>$last_name),
					array('name'=>'status', 'value'=>'New'),
					array('name'=>'phone_work', 'value'=> $phone),
					array('name'=>'email1', 'value'=>$email),
					array('name'=>'account_name','value'=>$company),
					array('name'=>'lead_source','value'=>'Web Site'),
					array('name'=>'description','value'=>$comments),
					array('name'=>'assigned_user_id', 'value'=>$this->user_id)
				);
				
				//	execute the create
				$result = $this->SetEntry('Leads',$data);
				
				//	log out of SugarCRM
				$this->Logout();
				
				if($this->send_email) {
					//	send notification email
					require_once ABSPATH . WPINC . '/class-phpmailer.php';
					require_once ABSPATH . WPINC . '/class-smtp.php';
					$mail = new PHPMailer();
					$mail->IsMail();
					$mail->AddAddress($this->notify_email);
					$mail->Subject = "New Web Lead";
					
					$body = "A new lead record was created in SugarCRM:\r\n\r\n";
					$body .= "First Name: $first_name\r\n";
					$body .= "Last Name: $last_name\r\n";
					$body .= "Company: $company\r\n";
					$body .= "Email: $email\r\n";
					$body .= "Phone: $phone\r\n";
					$body .= "Comments: $comments\r\n";
					
					$mail->Body = $body;

					if(!$mail->Send())
					{
					   throw new Exception("Error sending notification email: " . $mail->ErrorInfo);
					}
					
				}
				return true;
			}
		}
		catch(SoapFault $e){
			//	check the error and spit it out so we can try and fix it
			$fault_code = $e->faultcode;
			$fault_string = $e->faultstring;
			$fault_detail = $e->detail;
			$msg = "<p>The following error occured processing the request. Fault Code: $fault_code, Message: $fault_string</p>";
			echo $msg;
		}
		catch(Exception $e){
			echo 'Error: ' .$e->getMessage();
		}	
	}
	
}
?>