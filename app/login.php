<?php
include("../components/header.php");
include("../Class/class.inc.php");
$con = new Config();

if (isset($_POST["btn_login"])){
	extract($_POST);

	if ($email == "" || $password == ""){
		$err = " <span style='color:red;'>Please provide required information.</span>";
	} else {
		$output = $con->SelectAllByCondition("user_accounts", " email_address='$email' AND password='$password'");
		if (count($output) > 0){
			$con->redirect('profile.php');
		} else {
			$err = "<span style='color:red;'>Email and password did not match.</span>";
		}
	}
}
?>
<body>
	<?php include("../components/menu.php");?>
	<hr />
	<div class="col-md-12" style="font-size:12px;">
		<h4 style="text-align: center;"> Login </h4>

		<form method="post" name="frmLogin">
		    <div class="col-md-4">
		    	<p style="text-align: center;">
		    	<?php
					echo $msg;
					echo $err;
				?>
				<p>
				<label for="Email Address"><b>Email Address</b></label> <span style="color:red;"> * </span>
				<br />
				<input type="text" name="email" class="form-control">
				<br />
				<label for="Email Address"><b>Password</b></label> <span style="color:red;"> * </span>
				<br />
				<input type="password" name="password" class="form-control">
				<br />
				<input type="submit" value="Login" name="btn_login" class="btn btn-primary">
			</div>
		</form>
	</div>
</body>
</html>
