<?php
class User_notifications_model extends CI_Model {
	
	/**
	* Responsable for auto load the database
	* @return void
	*/
	var $twilio_AccountSid 	= '';
	var $twilio_AuthToken 	= '';
	var $twilio_mobile_no 	= '';
	var $mobile_nos_to_send 	= array();
	var $sms_messaages 		= array();
	var $verification_ids 	= array();
	
	public function __construct()
	{
		$this->load->library('mongo_db');
		
		$all_settings 	= $this->mongo_db->get('settings');
		$this->twilio_AccountSid 	= (isset($all_settings[0]['twilio_AccountSid']) && (!empty($all_settings[0]['twilio_AccountSid']))) ? $all_settings[0]['twilio_AccountSid'] : '';
		$this->twilio_AuthToken 		= (isset($all_settings[0]['twilio_AuthToken']) && (!empty($all_settings[0]['twilio_AuthToken']))) ? $all_settings[0]['twilio_AuthToken'] : '';
		$this->twilio_mobile_no 		= (isset($all_settings[0]['twilio_mobile_no']) && (!empty($all_settings[0]['twilio_mobile_no']))) ? $all_settings[0]['twilio_mobile_no'] : '';
	}
	
	public function initialize($params = array())
	{
		if(!empty($params))
		{
			foreach($params as $r => $parapm)
			{
				$this->$r = $parapm;
			}
		}
		
		//if(!empty($this->mobile_nos_to_send) && (!empty($this->sms_messaages)))
		
		$this->main();
	}
		
	public function main()
	{
		require FILEUPLOADPATH."assets/twilio-php/Services/Twilio.php";
	
		// Step 2: set our AccountSid and AuthToken from https://twilio.com/console
		$AccountSid 	= $this->twilio_AccountSid;
		$AuthToken 	= $this->twilio_AuthToken;
	 
		// Step 3: instantiate a new Twilio Rest Client
		$client 		= new Services_Twilio($AccountSid, $AuthToken);
	
		// Step 4: make an array of people we know, to send them a message. 
		// Feel free to change/add your own phone number and name here.
		//$people 		= array(
		//				"+15558675309" => "Curious George",
		//				"+15558675308" => "Boots",
		//				"+15558675307" => "Virgil",
		//			);
	
		// Step 5: Loop over all our friends. $number is a phone number above, and 
		// $name is the name next to it
		foreach ($this->mobile_nos_to_send as $n => $number) {
	 
			$sms_messaage = (isset($this->sms_messaages[$n]) && !empty($this->sms_messaages[$n])) ? $this->sms_messaages[$n] : '';
	 
			try {
				$sms = $client->account->messages->sendMessage(
	 
					// Step 6: Change the 'From' number below to be a valid Twilio number 
					// that you've purchased
					$this->twilio_mobile_no, 
		 
					// the number we are sending to - Any phone number
					$number,
		 
					// the sms body
					$sms_messaage
				);
				
				
			} catch (Exception $e) {
				//return true;
			}
			
			// Display a confirmation message on the screen
			//echo "Sent message to $name";
		}
	}
}

?>