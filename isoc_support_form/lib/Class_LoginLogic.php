<?php 
require_once 'Class_LoginDB_In.php';
require_once 'Class_LoginDB_Out.php';
require_once 'Class_ValidationUserInput.php';
require_once 'Class_ISOC_EMAIL.php';

class LoginLogic extends ValidationUserInput
{
	private $FromDB;
	private $ToDB;
	private $email;
	private $validation;
	//private $targetLink;

	// Constructor
	function LoginLogic()
	{
		$this->instantiateVariables();
	}
	// Instantiate Variables used in this class
	private function instantiateVariables()
	{
		 // Database communication variables
		 $this->FromDB = new LoginDB_Out();
		 $this->ToDB = new LoginDB_In();
		 $this->email = new ISOC_EMAIL();		
		 $this->validation = new ValidationUserInput();
		// $this->setTagertLink( '' );
	}
	/*
	private function setTagertLink( $aLink )
	{
		$this->targetLink = $aLink;
	}
	
	private function getTagetLink()
	{
		return $this->targetLink;
	}
    */

	public function getError( $nameOfObject )
	{
		$this->validation->input_error( $nameOfObject );
	}
	
	
	
	// custom methods determined by fields on form
	private function callValidationMethodsLoginForm()
	{
		$temp = array();
		
		$temp[] = $this->validation->validateInformation( 'username' , 'ALL');
		$temp[] = $this->validation->validateInformation( 'password' , 'ALL');
		
		foreach ($temp as $value)
		{
			if ($value === false)
			{
				//echo "The Value is:  ".$value;
				return false;
			}
		}
		// all items have passed validation
		return true;
	}
	

	
	private function checkLoginPassword()
	{
		$temp = array();
		
		$username = $_POST['username'];
		$password = $_POST['password'];
		
		$sql = "SELECT * FROM TB_ISOC_TECHS WHERE ISOC_TECH_EMPLOYEE_ID = '".$username."' OR ISOC_TECH_EMAIL = '".$username."' AND ISOC_TECH_PASSWORD = '".$password."'";
		$temp = $this->FromDB->multiFieldChangeToArrayAssociative( $sql );		
		
	
		if ($temp['ISOC_TECH_PASSWORD'] === $password )
		{
			// Start a new session
			$this->startSession($temp);
			
			// update last login time in database
			$this->updateLastLogin();
			
			$url = $_SESSION['actual_link'];
			
		session_write_close();
			
			// Javascript Redirct
			echo"
		<script>
				window.location = '".$url."';
		</script>";

			exit();
			
		}
		else
		{
			echo "incorrect login";
		}
	}
	
	private function callValidationMethodsRegisterForm()
	{
		$temp = array();
		
		$temp[] = $this->validation->validateInformation( 'email' , 'EMAIL');
		$temp[] = $this->validation->validateInformation( 'id' , 'INT');
		$temp[] = $this->validation->validateInformation( 'firstname' , 'LETTER');
		$temp[] = $this->validation->validateInformation( 'lastname' , 'LETTER');
		$temp[] = $this->validation->validateInformation( 'passwd' , 'ALL');
		$temp[] = $this->validation->validateInformation( 'secretWord' , 'ALL');
		
		
		foreach ($temp as $value)
		{
			if ($value === false)
			{
				//echo "The Value is:  ".$value;
				return false;
			}
		}
		// all items have passed validation
		return true;
	}
	
	private function checkLoginPasswordRegister()
	{
		$email = $_POST['email '];
		$id = $_POST['id'];
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$passwd = $_POST['passwd'];
		$secretWord = $_POST['secretWord'];
		$dateTimeNow = date('Y-m-d H:i:s');
		$anArray = array("ISOC_TECH_EMPLOYEE_ID"=>"$id", "ISOC_TECH_PASSWORD"=>"$passwd", "ISOC_TECH_FIRST_NAME"=>"$firstname", "ISOC_TECH_LAST_NAME"=>"$lastname",
						 "ISOC_TECH_EMAIL"=>"$email", "ISOC_TECH_SECRET_WORD"=>"$secretWord", "ISOC_TECH_LAST_LOGIN"=>"$dateTimeNow");
		
		
		// Insert New User
		$ToDB->insertRecordOneTable( $anArray ,'TB_ISOC_TECHS', $fieldTypes = 'issssss' );
		
		
		$sql = "SELECT * FROM TB_ISOC_TECHS WHERE ISOC_TECH_EMPLOYEE_ID = '".$id."' OR ISOC_TECH_EMAIL = '".$email."' AND ISOC_TECH_PASSWORD = '".$passwd."'";
		$temp = $this->FromDB->multiFieldChangeToArrayAssociative( $sql );		
		
	
		if ($temp['ISOC_TECH_PASSWORD'] === $passwd )
		{
			// Start a new session
			$this->startSession($temp);
			
			// update last login time in database
			$this->updateLastLogin();
			
			$url = $_SESSION['actual_link'];
			
			//Send EMAIL confirmation
			//$this->requesterEmailSend();
			
			
			session_write_close();
			
			// Javascript Redirct
			echo"
		<script>
				window.location = '".$url."';
		</script>";

			exit();
			
		}
		else
		{
			echo "Failed to register!";
		}
	}
	
	private function createRequesterEmailBody()
	{	
		$message = 'Below is a summary of your account information.<br />
					<br />
					<b><u>ID: '.$_SESSION['ISOC_TECH_EMPLOYEE_ID'].'</u></b><br />
					<b>Email:</b>      '.$_SESSION['ISOC_TECH_EMAIL'].'<br /> 
					<b>Secret Word:</b>  '.$_SESSION['ISOC_TECH_SECRET_WORD'].'<br />
					<b>First Name:</b>       '.$_SESSION['ISOC_TECH_FIRST_NAME'].'<br />
					<b>Last Name:</b> '.$_SESSION['ISOC_TECH_LAST_NAME'].'<br />
					<b>Password:</b>      '.$_SESSION['ISOC_TECH_PASSWORD'].'<br />
					<br />
					<br />
					<br />
					<br />
					Thanks for using the ISOC login form,<br />
					IS Operations
					
					
					
					';
					
		return $message;
	}
	
	private function requesterEmailSend()
	{
		// create email message
		$message = '
			
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>ISOC Request Conformation Email</title>

		</head>

		<body bgcolor="#f2eded">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#f2eded">
		  <tr>
			<td><table width="600" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" align="center">
				<tr>
				  <td valign="middle">
				  
				  <div style="text-align: center;font-family: Helvetica; font-variant: small-caps; color: #FFFFFF;  background: #66C285;">ISOC Request Form Conformation Email</div>
					
					</td>
				</tr>
				<tr>
				  <td align="center">&nbsp;</td>
				</tr>
				<tr>
				  <td>&nbsp;</td>
				</tr>
				<tr>
				  <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td width="10%">&nbsp;</td>
						<td width="80%" align="left" valign="top"><font style="font-family: Georgia, "Times New Roman", Times, serif; color:#010101; font-size:24px"><strong><em>Hi '.$_SESSION['ISOC_TECH_FIRST_NAME'].',</em></strong></font><br /><br />
						  <font style="font-family: Verdana, Geneva, sans-serif; color:#666766; font-size:13px; line-height:21px">
						  
						  '.$this->createRequesterEmailBody().'
							<br />
							<br />
							<a href="http://10.176.105.18/isoc_support_form/login.php">ISOC Dashboard</a>
						</font>
						
						</td>
						<td width="10%">&nbsp;</td>
					  </tr>
					  
					  
					  
					  <tr>
						<td>&nbsp;</td>
						<td align="right" valign="top"></td>
						<td>&nbsp;</td>
					  </tr>
					</table></td>
				</tr>
				<tr>
				  <td>&nbsp;</td>
				</tr>
				<tr>
				  <td>&nbsp;</td>
				</tr>
				<tr>
				  
				</tr>
				
			  </table></td>
		  </tr>
		</table>
		</body>
		</html>


		';
			
			// set the message
			$this->email->setMessage( $message );
			
			// set the To field of email
			$this->email->setTo( $_SESSION['ISOC_TECH_EMAIL'] );
			
			$this->email->setFrom( 'ISOperationsCenter@uscellular.com');
			
			// set the subject field of email
			$this->email->setSubject( 'New Registration Confirmation' );
			
			// prepare headers which informs the mail client that this will be html and the from and to
			$this->email->setHeaders();
			
			// send email
			$this->email->sendEmail();
	}
	
	// In seconds default 12 hours
	private function setSessionTime( $timeSeconds = 43200)
	{
		
		if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $timeSeconds)) 
		{
			
			session_unset();     // unset $_SESSION variable for the run-time 
			session_destroy();   // destroy session data in storage
			echo "Session destroyed";
		}
	
		$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
	}
	
	
	private function startSession( $anArray )
	{
		session_start();
		
		// Set the session time to 12 hours
		$this->setSessionTime();
		
		// Check if user has logged in yet.
		if (empty($_SESSION['ISOC_TECH_EMPLOYEE_ID']) ) 
		{

			$_SESSION = array_merge($anArray, $_SESSION); // Initializing Session			
		}
	}

	// Track last login into database
	private function updateLastLogin()
	{
		$date = date("Y-m-d H:i:s");
		$aSession = $_SESSION['ISOC_TECH_EMPLOYEE_ID'] ;
		$updateArray = array("ISOC_TECH_LAST_LOGIN"=>"$date");
		$whereArray = array("ISOC_TECH_EMPLOYEE_ID"=>"$aSession");
		
		// Update the last login time
		$this->ToDB->updateRecordOneTable( $updateArray , $whereArray, 'equals', 'TB_ISOC_TECHS' , 'ss');
	}
	
	
//Used to check information after user has "posted" the data from a from
// Used by the login form only.
	public function checkPOSTLoginInfo()
	{
	    // Checks to see if user has posted before checking any validation
		if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username']) ) 
		{
			if ($this->callValidationMethodsLoginForm() )
			{
				// check if login was successful
				$this->checkLoginPassword();
			}
			else
			{
				echo "Did not pass validation for login";
				// Maybe add a popup stating login failed please try again.
				
			}
		}
	}
	
	
	public function checkPOSTRegisterInfo()
	{
	    // Checks to see if user has posted before checking any validation
		if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id']) )
		{
			if ( $this->callValidationMethodsRegisterForm() )
			{
				// check if login was successful
				$this->checkLoginPasswordRegister();
			}
			else
			{
				echo '<script type="text/javascript"> $("#loginbox").hide(); $("#signupbox").show(); </script>';
				echo "Did not pass validation for registration";
				// Maybe add a popup stating login failed please try again.
				
			}
		}
	}

	// Should be called on all websites that require login.
	public function checkSession ()
	{		
         // must be on all pages
		 session_start();	
		 
		if ( empty($_SESSION['ISOC_TECH_EMPLOYEE_ID']) )
		{
			
			$_SESSION['actual_link'] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			echo "Is empty";
			session_write_close();
			header("location: login.php");
			
			exit();
		}
		
	}	
}
?>