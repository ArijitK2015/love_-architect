<?php
class user_email_model extends CI_Model {
	/**
	* Responsable for auto load the database
	* @return void
	*/
	public function __construct()
	{
		$this->load->library('mongo_db');
	}
	
	public function send_email($to='', $sub='', $msg='', $cc='', $bcc='', $attachments='', $attachments_name='', $to_name=''){
		
		$settings_arr 	= $this->mongo_db->get('settings');
		$settings		= (isset($settings_arr[0])) ? $settings_arr[0] : array();
		
		$smtp_server 	= (!empty($settings['smtp_server'])) 		? trim($settings['smtp_server']) 	: 'smtp.gmail.com';
		$smtp_port 	= (!empty($settings['smtp_port'])) 		? trim($settings['smtp_port']) 	: '25';
		$smtp_username = (!empty($settings['smtp_username'])) 		? trim($settings['smtp_username']) 	: '';
		$smtp_password = (!empty($settings['smtp_password'])) 		? trim($settings['smtp_password']) 	: '';
		
		//$smtp_server		= 'smtp.gmail.com';
		//$smtp_port		= '25';
		//$smtp_username		= 'testing.esolz@gmail.com';
		//$smtp_password		= 'esolz1234';
		
		$site_email 	= (!empty($settings['system_email'])) 		? $settings['system_email'] 	: 'info@hotcargo.com';
		$site_name 	= (!empty($settings['site_name'])) 		? $settings['site_name'] 	: 'Hotcargo';
		
		//echo '</pre>'; echo $smtp_server.'->'.$smtp_port.'->'.$smtp_username.'->'.$smtp_password.'->'.$site_email.'->'.$site_name; 
		
		date_default_timezone_set('Etc/UTC');

		require_once (FILEUPLOADPATH.'/assets/PHPMailer/PHPMailerAutoload.php');

		//Create a new PHPMailer instance
		$mail 				= new PHPMailer;
		//Tell PHPMailer to use SMTP
		$mail->isSMTP();
		//Enable SMTP debugging
		// 0 = off (for production use)
		// 1 = client messages
		// 2 = client and server messages
		
		$mail->SMTPDebug 		= 0;
		//Ask for HTML-friendly debug output
		$mail->Debugoutput 		= 'html';
		//Set the hostname of the mail server
		$mail->Host 			= $smtp_server;
		//Set the SMTP port number - likely to be 25, 465 or 587
		$mail->Port 			= $smtp_port;
		//Whether to use SMTP authentication
		$mail->SMTPAuth 		= true;
		//Username to use for SMTP authentication
		$mail->Username 		= $smtp_username;
		//Password to use for SMTP authentication
		$mail->Password 		= $smtp_password;
		
		//Set who the message is to be sent from
		$mail->setFrom($site_email, $site_name);
		
		//Set an alternative reply-to address
		$mail->addReplyTo($site_email, $site_name);
		
		// Set email format to HTML
		$mail->isHTML(true);
		
		$mail->ContentType 		= 'text/html';
		
		//Set who the message is to be sent to
		if(is_array($to)){
			foreach($to as $i=>$to_one){
				$to_name_one = (isset($to_name[$i])) ? $to_name[$i] : '';
				
				$mail->addAddress($to_one, $to_name_one);
			}
		}
		else
			$mail->addAddress($to, $to_name);
			
		
		//Add cc to this email
		if(!empty($cc)){
			if(is_array($cc)){
				foreach($cc as $i=>$to_cc){
					$mail->addCC($to_cc);
				}
			}
			else
				$mail->addCC($cc);
		}
		
		//Add bcc to this email
		if(!empty($bcc)){
			if(is_array($bcc)){
				foreach($bcc as $i=>$to_bcc){
					$mail->addBCC($to_bcc);
				}
			}
			else
				$mail->addBCC($bcc);
		}
		
		//Set the subject line
		$mail->Subject 		= $sub;
		
		//convert HTML into a basic plain-text alternative body
		$mail->MsgHTML( $msg );
		
		//Replace the plain text body with one created manually
		$mail->AltBody = 'This is a plain-text message body';
		
		//Attach a file
		if(!empty($attachments)){
			if(is_array($attachments)){
				foreach($attachments as $i=>$attachment){
					$att_name = (isset($attachments_name[$i])) ? $attachments_name[$i] : '';
					$mail->addAttachment($attachment, $att_name);
				}
			}
			else
				$mail->addAttachment($attachments, $attachments_name);
		}
		
		//send the message, check for errors
		if (!$mail->send()) {
		    //echo "<br><br> Mailer Error: " . $mail->ErrorInfo;
		} else {
		    //echo "mail sent";
		}
		
		//die;
		return true;
	}
}

?>