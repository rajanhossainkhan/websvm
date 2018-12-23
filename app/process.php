<?php
session_start();
include("../components/header.php");
include("../Class/class.inc.php");
$con = new Config();
	/**
 * Uplaod the text file
 * Shows options - train, scale, easy.py
*/
$current_url = explode("/",$_SERVER['REQUEST_URI']);
$current_page = substr($current_url[3], 0, -4);

//If not logged in then redirect
$user_id_session = 0;
if (!isset($_SESSION['user_id'])){
	$con->redirect("login.php?return_back_url=" . $current_page);
}else {
	//Store the session to name target directory for each user
	$user_id_session = $_SESSION["user_id"];
}


if (isset($_POST["frmSubmit"])){
	extract($_POST);

	/**
	 * File is required
	 * Only txt files are allowed
	 *
	 */
	if(isset($_FILES['trainingFile'])){
		$errors = "";

		//Generate a reference number and appened to file name
		$reference_number = uniqid();

		//Retrieve file information and save into db
		$file_name = $_FILES['trainingFile']['name'];
		$file_size =$_FILES['trainingFile']['size'];
		$file_tmp =$_FILES['trainingFile']['tmp_name'];
		$file_type=$_FILES['trainingFile']['type'];
		$file_ext=strtolower(end(explode('.',$_FILES['trainingFile']['name'])));

		$extensions= array("txt"); //Declare array for allowing more than one file type in future

		if(in_array($file_ext,$extensions)=== false){
			$error ="Extension not allowed, please choose a txt file to train your model.";
		}

		if($error == ""){
			//Create directory for the user if not yet created
			$file_path = '../Uploads/TrainingFiles/' . $_SESSION['user_id'];
			if (!file_exists($file_path)) {
				mkdir($file_path, 0777, true);
			}

			$final_file_name = $reference_number . "_" . microtime(true)  . "_" .  $file_name;
			$fully_qualified_path = $file_path . "/" . $final_file_name;

			//Insert a record in the db for uploaded file for the user
			$insert_array = array(
				"FileNameGiven" => $trainingFileTitle,
				"FileName" => $file_name,
				"FileType" => "train",
				"FilePath" => $fully_qualified_path,
				"UserId" => $user_id_session,
				"UpdateDate" => date("Y-m-d H:i:s"),
				"UpdateBy" => $user_id_session
			);
			if ($con->insert("UserFiles", $insert_array) == 1){
				//Upload file to target directory
				move_uploaded_file($file_tmp,$fully_qualified_path);
				$msg = "File is succesfully uploaded. <a href='training.php?TargetFile=".$final_file_name."&ref=".$reference_number."' class='btn btn-primary'>Proceed to Training Page</a>";
			} else {
				$err = "File upload failed.";
			}
		}else{

		}
	}

}
?>

	<body>
		<?php include("../components/menu.php");?>
		<hr />
		<div class="col-md-12" style="font-size:12px;">

			<?php
			echo $msg;
			echo $error;
			?>
			<br />

			<form method="POST" action="process.php" enctype="multipart/form-data">
				Upload Sparse Matrix File-
				<br /><br />
				<input type="text" name="trainingFileTitle" class="form-control">
				<br />
				<input type="file" name="trainingFile" class="form-control">
				<br />
				<input type="submit" value="Upload Training File" name="frmSubmit" class="btn btn-primary">
			</form>
		</div>
	</body>
	</html>
