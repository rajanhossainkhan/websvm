<?php
session_start();
include("../components/header.php");
include("../global.config.php");
include("../Class/class.inc.php");
$con = new Config();

//If not logged in then redirect
$current_url = explode("/",$_SERVER['REQUEST_URI']);
$current_page = substr($current_url[3], 0, -4);

$user_id_session = 0;
if (!isset($_SESSION['user_id'])){
	$con->redirect("login.php?return_back_url=" . $current_page);
}else {
	//Store the session to name target directory for each user
	$user_id_session = $_SESSION["user_id"];
}



//Check the user from session and find reight directory
//Grab the file name from URL and use it to predict
$TargetFile = "";
$FileNameOnly = "";
if (isset($_GET["TargetFile"]) && $_GET["TargetFile"] != ""){
	$TargetFile = $_GET["TargetFile"];
	$FileNameOnly_array =  explode("_", $TargetFile);
	array_shift($FileNameOnly_array);
	$FileNameOnly_final = implode($FileNameOnly_array, "_");
}

/**
 * Read file directory and show the file to user
 * At this moment, only one file is available
 * Later, user based file management will be added
 */
/**
 * Read file directory and show the file to user
 */
$msg = "";
$error = "";


/**
 * Uplaod the text file
 * Shows options - train, scale, easy.py
*/

if (isset($_POST["frmSubmit"])){

	/**
	 * File is required
	 * Only txt files are allowed
	 *
	 */
	if(isset($_FILES['testFile'])){
		$errors = "";

		//Retrieve file information and save into db
		$file_name = $_FILES['testFile']['name'];
		$file_size = $_FILES['testFile']['size'];
		$file_tmp  = $_FILES['testFile']['tmp_name'];
		$file_type = $_FILES['testFile']['type'];
		$file_ext  = strtolower(end(explode('.',$_FILES['testFile']['name'])));

		$extensions= array("txt"); //Declare array for allowing more than one file type in future

		if(in_array($file_ext,$extensions)=== false){
			$error ="Extension not allowed, please choose a txt file to train your model.";
		}

		if($error == ""){
			//Create a directory or check exisiting
			//Create directory for the user if not yet created
			$file_path =  $test_dataset_directory . $_SESSION['user_id'] . "/";
			if (!file_exists($file_path)) {
				mkdir($file_path, 0777, true);
			}

			$final_file_name = microtime(true)  . "_" .  $file_name;
			$fully_qualified_path = $file_path . "/" . $final_file_name;
			$_SESSION["FileInfo"]["TestFile"] =  $fully_qualified_path;

			//Insert a record in the db for uploaded file for the user
			$insert_array = array(
				"FileNameGiven" => "",
				"FileName" => $file_name,
				"FileType" => "test",
				"FilePath" => $fully_qualified_path,
				"UserId" => $user_id_session,
				"UpdateDate" => date("Y-m-d H:i:s"),
				"UpdateBy" => $user_id_session
			);
			if ($con->insert("UserFiles", $insert_array) == 1){
				move_uploaded_file($file_tmp, $fully_qualified_path);
				$msg = "Test file succesfully uploaded.";
			} else {
				$error = "Something went wrong. File upload failed.";
			}
		}else{
			//Nothing happens
		}
	}
}

//Run predict command
//Redirect to result page
if (isset($_POST["predictSubmit"])){
	$ModelFile = $models_directory . "/" . $user_id_session . "/" . $TargetFile;
	$TestFile = $_SESSION["FileInfo"]["TestFile"];

	echo "working";
	echo $ModelFile; echo "<br />";

	//Create directory for the user if not yet created
	$file_path =  $output_directory . $_SESSION['user_id'] . "/";
	if (!file_exists($file_path)) {
		mkdir($file_path, 0777, true);
	}

	//Use generated model file in the previous step
	//Write the output file to OutputFiles directory
	shell_exec("../libsvm/./svm-predict " . $TestFile . " " . $ModelFile ." " . $file_path . "output." .$TestFile);
}
?>

<body>
	<?php include("../components/menu.php");?>
	<hr />

	<div class="col-md-12" style="font-size:12px;">
		<?php echo $msg; ?>
		<form method="POST" enctype="multipart/form-data">
			Trained Model File-
			<span style="color:blue; font-size:18px; font-weight: bold;">
				<?php echo $FileNameOnly_final;?>
			</span>
		    <br />
		    <span>Upload Test Data Set (Sparse Matrix Format):</span><br />
		    <input type="file" name="testFile" class="form-control">
		    <br />
		    <input type="submit" value="Upload" name="frmSubmit" class="btn btn-primary">
		</form>

		<form method='post'>
			<input type='submit' name='predictSubmit' class='btn btn-primary' value='Run Predict Command'>
		</form>
	</div>
</body>
</html>