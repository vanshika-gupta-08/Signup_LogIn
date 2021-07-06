<?php

session_start();

require_once("pdo.php");
function checkemail($str) {
         return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? true : false;
   }
   
   
    


if( isset($_POST['pswd'])  && isset($_POST['email']))
{

   $email_1= htmlentities($_POST['email']);
   $pswd_1= htmlentities($_POST['pswd']);
  

   if ( strlen($_POST['email']) < 1
   || strlen($_POST['pswd']) < 1) 
   {
       $_SESSION["error"] = "All fields are required are required";
	   header( "location:login.php");
	   return;
	   
	   }
	
	
	else if(checkemail($_POST['email']))
	{
     $_SESSION["error"] =  "Email must have an at-sign (@) " ;
	header( "location:login.php");
	   return;
	}
	else
	{
	try {
            
            $query = $pdo->prepare("SELECT * FROM users WHERE email =:email");
            $query->execute(array(':email'=>$email_1);
               $row = $query->fetch(PDO::FETCH_ASSOC);
            if ($query->rowCount() > 0) {
			if($email_1 == $row["email"] && $pswd_1== $row["password"]) )
			{
				$query = $pdo->prepare("SELECT link FROM links WHERE company =:company");
				$query->execute(array(':company'=> $row["company"]);
               $row = $query->fetch(PDO::FETCH_ASSOC);
			          header("location:$row");
		                  return;          
					
				}
				else
				{
					$_SESSION['error'] = "Incorrect Email or Password";
		header("location:login.php");
		return;
				}
			
			}
			else
			{
				$_SESSION['error'] = "Login Failed .Please register yourself";
		header("location:login.php");
		return;
			}
               
}
          
			
         catch (PDOException $e) {
            exit($e->getMessage());
        }
		
		}
    



	
	
	


require_once "phpmailer/class.phpmailer.php";
// my message to send to the user
$lastID = $pdo->lastInsertId();
$message = '<html><head>
           <title>User details</title>
           </head>
           <body>';
$message .= '<h1>Hi ' . $uname_1 . '!</h1>';
$message .= '<h5>Password: ' . $pswd_1 . '!</h5>';
$message .= "</body></html>";
// php mailer code starts
$mail = new PHPMailer(true);
// telling the class to use SMTP
$mail->IsSMTP();
// enable SMTP authentication
$mail->SMTPAuth = true;   
// sets the prefix to the server
$mail->SMTPSecure = "ssl"; 
// sets GMAIL as the SMTP server
$mail->Host = "smtp.gmail.com"; 
// set the SMTP port for the GMAIL server
$mail->Port = 465; 
// set your username here
$mail->Username = 'company@gmail.com';
$mail->Password = 'Vanshika@123';
// set your subject
$mail->Subject = trim("Email Verifcation - www.thesoftwareguy.in");
// sending mail from
$mail->SetFrom('company@gmail.com', 'vanshika Gupta');
// sending to
$mail->AddAddress($email);
// set the message
$mail->MsgHTML($message);
try {
  $mail->send();
} catch (Exception $ex) {
  echo $msg = $ex->getMessage();
}
}


?>

<html lang="en">
<head>
  <title>Project</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">WebSiteName</a>
    </div>
    <ul class="nav navbar-nav">
      <li class="active"><a href="index.html">Home</a></li>
      <li><a href="signup.php">signUp</a></li>
      <li><a href="#">LogIn</a></li>
      
    </ul>
  </div>
</nav>
  
<div class="container">
   <div class  = "container-fluid">
   <?php
   if(isset( $_SESSION["error"] ))                                             // for flash message
{
echo ("<p style = ' color: red ; '>".$_SESSION['error']."</p>" );     // for flash message
unset( $_SESSION["error"]);                                          // for flash message

}
?>

   <h2> --Login Page--</h2> <br>
             <form  method="post"  >
	
<div  class = "form-group">
<label for = "email" > <b>Email ID </b> </label>
<input   class = "form-control" type = "text" id = "email" name = "email"/><br>
</div>

<div  class = "form-group">
<label for = "pswd" ><b> Password </b> </label>
<input  class = "form-control" type = "password" id = "pswd" name = "pswd"/><br>
</div>
<input type = "submit" value = "LogIn" >


<a href = "login.php" style = "color:blue; text-decoration:none;" >Forgot Password </a>

</form>
   </div>
</div>

</body>
</html>
