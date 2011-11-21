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
		$this->notify_email = get_option("web2lead_notify_email");	
		$this->app = 'web2lead';
		$this->soap_client = new SoapClient(null, array(
				'location'=>$this->url,
				'uri'=>'http://www.sugarcrm.com/sugarcrm',
				'soap_version'=>'SOAP_1_1',
				'trace'=>1,
				'exceptions'=>1
				));
		$this->send_email = get_option("web2lead_enable_notify_email");
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
			
			//	check for error
			if($this->session_id == -1) {
				throw new Exception("Please check user name and password.");
			}

			//	get user id
			$this->user_id = $this->soap_client->get_user_id($session_id);
			return true;
		}
		catch(Exception $e) {
			throw new Exception('Error logging into SugarCRM: '.$e->getMessage());
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
				//	get our mappings & create data array
				$mappings = unserialize(get_option("web2lead_mappings"));
				$data = array();
				foreach($mappings as $mapping) {
					$val = $mapping['value'];
					if($mapping['type']=="field") {
						//	grab value from the form post
						$val = $_POST[$mapping['value']];
					}
					$data[] = array('name'=>$mapping['target'],'value'=>$val);
				}

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
					$fields = unserialize(get_option("web2lead_form_fields"));
					foreach ($fields as $field) {
						//	each feld is stored as an array
						$field_name = $field["id"];
						$field_display = $field["display"];
						//	grab value from the form post
						$val = $_POST[$field_name];
						$body .= $field_display.": ".$val."\r\n";
					}			
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