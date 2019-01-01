<?php
/*
* List down files generated by easy.py based on logged in user
* Author: Rajan Hossain
* Date: December 15, 2018
*/
session_start();
include("../components/header.php");
include("../global.config.php");
include("../Class/class.inc.php");
$con = new Config();

//If not logged in then redirect
$current_url = explode("/",$_SERVER['REQUEST_URI']);
$current_page = substr($current_url[3], 0, -4);

//if no session user id was not available, redirect to login
$user_id_session = 0;
if (!isset($_SESSION['user_id'])){
	$con->redirect("login.php?return_back_url=" . $current_page);
}else {
	//Store the session to name target directory for each user
	$user_id_session = $_SESSION["user_id"];
}

//Collect ref number from URL
$reference_number = "";
if (isset($_GET["ref"])){
	$reference_number = $_GET["ref"];
}
if ($reference_number != ""){
	$autofiles = $con->SelectAllByCondition("UserFiles", "reference_number='$reference_number' AND UserId='$user_id_session'");
	//Remove first element
	array_shift($autofiles);
}else{
	$autofiles = array();
}

$file_path = "../AutomaticFiles/{$user_id_session}/";
?>

<!--User area-->
<?php include("../components/menu.php");?>
<hr />

<div class="row">
<div class="col-md-6" style="padding-left:35px;">
<?php
	echo "<h4>List of files: </h4>";
	echo "<table style='width:90%;'>";
	foreach ($autofiles as $file){
		echo "<tr>";
		echo "<td>" .$file->FileName . "</td>";
		echo "<td style='padding-left:10px;'><a href='{$file->FilePath}' target='_blank'  class='btn btn-primary btn-sm'>Download</a></td>";
		echo "</tr>";
	}
	echo "</table>";
?>
</div>
<div class="col-md-6">
	<h4 style="text-align:center;">Performance Vector</h4>
	<img src="<?php echo $autofiles[6]->FilePath; ?>" style="height:600px; width:600px;">
</div>
<div class="clearfix"></div>
</div>



