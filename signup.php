<?php

session_start();

require_once("pdo.php");
function checkemail($str) {
         return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? true : false;
   }
   function checkusername($str) {
         return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)([0-9\-])+[a-z]{2,6}$/ix", $str)) ? true : false;
   }
   
    


if(isset($_POST['cname']) && isset($_POST['uname'])  && isset($_POST['gstno'])
&& isset($_POST['pswd'])  && isset($_POST['email']))
{

   $cname_1= htmlentities($_POST['cname']);
   $gstno_1= htmlentities($_POST['gstno']);
   $uname_1= htmlentities($_POST['uname']);
   $email_1= htmlentities($_POST['email']);
   $pswd_1= htmlentities($_POST['pswd']);
  

   if ( strlen($_POST['cname']) < 1 || strlen($_POST['gstno']) < 1
   || strlen($_POST['uname']) < 1 || strlen($_POST['email']) < 1
   || strlen($_POST['pswd']) < 1) 
   {
       $_SESSION["error"] = "All fields are required are required";
	   header( "location:signup.php");
	   return;
	   
	   }
	else if(! is_numeric($gstno_1) )
	{
		$_SESSION['error'] = "GST No. must be numeric";
		header("location:signup.php");
		return;
	}
	
	
	else if(checkemail($_POST['email']))
	{
     $_SESSION["error"] =  "Email must have an at-sign (@) " ;
	header( "location:signup.php");
	   return;
	}
	
	$sql = "SELECT COUNT(*) AS count from users where email = :email_id";
    try {
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":email_id", $email_1);
    $stmt->execute();
    $result = $stmt->fetchAll();
    if ($result[0]["count"] > 0) {
      $_SESSION['error'] = "Email already Exists";
		header("location:signup.php");
		return;
    } else {
      $sql = "insert into users(username,email,password) values(:uname,:email,:pswd)";
		$stml = $pdo->prepare($sql);
		$stml->execute (array(
		':uname' => $uname_1,
	     ':email' => $email_1,
		':pswd' => $pswd_1));


		// $_SESSION['success'] = "Registration successful!! Please logIn to proceed further.";
		header("location:success.php");
		return;
      
    }
  } catch (Exception $ex) {
    echo $ex->getMessage();
  }
	
	
	


 $to = "$email_1";
         $subject = "User Details";
         
         $message = "<b>'Hii,'.$uname_1.</b>";
         $message .= "<h1>Password : $pswd_1.</h1>";
         
         $header = "From:abc@somedomain.com \r\n";
         $header .= "Cc:afgh@somedomain.com \r\n";
         $header .= "MIME-Version: 1.0\r\n";
         $header .= "Content-type: text/html\r\n";
         
         $retval = mail ($to,$subject,$message,$header);
}
?>

<!DOCTYPE html>
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
      <li><a href="#">signUp</a></li>
      <li><a href="login.php">LogIn</a></li>
      
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

   <h2> --Registration Form--</h2> <br>
             <form  method="post"  >
	<div  class = "form-group">		
<label for = "cname" > <b>Company Name </b> </label>
<input class = "form-control" type = "text" id = "cname" name = "cname"/><br>
</div>
<div  class = "form-group">
<label for = "gstno" > <b>GST No.</b> </label>
<input   class = "form-control" type = "text" id = "gstno" name = "gstno"/><br>
</div>
<div  class = "form-group">
<label for = "email" > <b>Email ID </b> </label>
<input   class = "form-control" type = "text" id = "email" name = "email"/><br>
</div>
<div  class = "form-group">
<label for = "uname" > <b>User Name </b> </label>
<input  class = "form-control" type = "text" id = "uname" name = "uname"/><br>
</div>
<div  class = "form-group">
<label for = "pswd" ><b> Password </b> </label>
<input  class = "form-control" type = "password" id = "pswd" name = "pswd"/><br>
</div>
<input type = "submit" value = "Register" >


<a href = "signup.php" style = "color:blue; text-decoration:none;" >Cancel </a>

</form>
   </div>
</div>

</body>
</html>
