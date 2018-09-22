<?php
include("../components/header.php");
include("../Class/class.inc.php");
$con = new Config();

if (isset($_POST["btn_add_account"])){
	extract($_POST);

	if ($fullname == "" || $email == "" || $password == ""){
		$err = " <span style='color:red;'>Please provide required information.</span>";
	} else {
		$insert_array = array(
			"user_fullname" => $fullname,
			"email_address" => $email,
			"password" => $password,
			"create_date" => date("Y-m-d H:i:s"),
			"mod_date" => date("Y-m-d H:i:s")
		);
		$output = $con->insert("user_accounts", $insert_array);
		if ($output == 1){
			$msg = "<span style='color:green;'>Account is succefully created.</span>";
		} else {
			$err = "<span style='color:red;'>Account creation failed.</span>";
		}
	}
}
?>
<body>
	<?php include("../components/menu.php");?>
	<hr />
	<div class="col-md-12" style="font-size:12px;">
		<h4 style="text-align: center;"> Signup </h4>

		<form method="post" name="frmSignup">
		    <div class="col-md-4">
		    	<p style="text-align: center;">
		    	<?php
					echo $msg;
					echo $err;
				?>
				<p>
				<label for="Full Name"><b>Full Name</b></label> <span style="color:red;"> * </span>
				<br />
				<input type="text" name="fullname" class="form-control">
				<br />
				<label for="Email Address"><b>Email Address</b></label> <span style="color:red;"> * </span>
				<br />
				<input type="text" name="email" class="form-control">
				<br />
				<label for="Email Address"><b>Password</b></label> <span style="color:red;"> * </span>
				<br />
				<input type="password" name="password" class="form-control">
				<br />
				<input type="submit" value="Create Account" name="btn_add_account" class="btn btn-primary">
			</div>
		</form>
	</div>
</body>
</html>
