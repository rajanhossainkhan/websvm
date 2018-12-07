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
 */
$msg = "";
$train_flag = 0;
$dir    		= $files_directory . "/" . $user_id_session . "/";
$training_files = scandir($dir);


//Grab training file
$training_file = $training_files[3];

/**
 * Uplaod the text file
 * Shows options - train, scale, easy.py
*/

if (isset($_POST["frmSubmit"])){
	/**
	 * Check the directory before running the training command
	 * Depending on the file size, trainign might take longer time
	 */
	

	//Create a directory or check exisiting
	//Create directory for the user if not yet created
	$file_path = '../LearningModels/' . $_SESSION['user_id'];
	if (!file_exists($file_path)) {
		mkdir($file_path, 0777, true);
	}
	$fully_qualified_path = $file_path . "/" . $TargetFile . ".model";


	/**
	* Assume uploaded file is a right svm file
	* Run train command against it.
	*/
	shell_exec("../libsvm/./svm-train " . $files_directory . $user_id_session . "/" . $TargetFile);
	
	//Based on the output from the shell,
	//Determine if the process completed successfully

	//Once the process is complete, make record of model file in db
	$insert_array = array(
		"FileNameGiven" => $trainingFileTitle,
		"FileName" => $TargetFile,
		"FileType" => "model",
		"FilePath" => $fully_qualified_path,
		"UserId" => $user_id_session,
		"UpdateDate" => date("Y-m-d H:i:s"),
		"UpdateBy" => $user_id_session
	);
	$con->insert("UserFiles", $insert_array);
	
		$msg = "Classification is successfull. A result file is generated
	<a href='predict.php?TargetFile=".$TargetFile.".model' class='btn btn-primary'> Proceed to Result</a>";
}

if (isset($_POST['frmSubmitAuto'])){
	echo "Working";
	/**
	 * Check the directory before running the training command
	 * Depending on the file size, trainign might take longer time
	 */

	//Create a directory or check exisiting
	//Create directory for the user if not yet created
	$file_path = '../AutomaticFiles/' . $_SESSION['user_id'] . '/';
	if (!file_exists($file_path)) {
		mkdir($file_path, 0777, true);
	}	

	//Make record in database
	//Insert a record in the db for uploaded file for the user
	$insert_array = array(
		"FileNameGiven" => $trainingFileTitle,
		"FileName" => $FileNameOnly_final,
		"FileType" => "Auto",
		"FilePath" => $file_path,
		"UserId" => $user_id_session,
		"UpdateDate" => date("Y-m-d H:i:s"),
		"UpdateBy" => $user_id_session
	);
	if ($con->insert("UserFiles", $insert_array) == 1){
		/**
		 * Run easy.py command
		 * Filenames should have the user ID associated with it
		 * Move the target files [specific files generated in current session]
		 * To AutomaticFiles for each user  
		 */
		shell_exec("../libsvm/./svm-train " . $files_directory . $user_id_session . "/" . $TargetFile . " " . $file_path . "/" .$TargetFile .".model");
		$msg = "Process is successfull. All required files are  generated.
		<a href='predict.php?TargetFile=".$TargetFile.".model' class='btn btn-primary'> Output</a>";
	} else {
		$err = "Something went wrong. Training failed.";
	}

	//Make record of output file (SVM) in the db
	//Easy.py, unlike default prediction model,
	//performs cross validation and parameter tuning
	//Output file would contain the combination of C and G.
	$insert_array = array(
		"FileNameGiven" => "",
		"FileName" => $TargetFile . ".scale.out",
		"FileType" => "output",
		"FilePath" => $fully_qualified_path,
		"UserId" => $user_id_session,
		"UpdateDate" => date("Y-m-d H:i:s"),
		"UpdateBy" => $user_id_session
	);
	$con->insert("UserFiles",$insert_array);
}
?>

<body>
	<?php include("../components/menu.php");?>
	<hr />

	<div class="col-md-12" style="font-size:12px;">
		<?php echo $msg; ?>
		<form method="POST">
			Uploaded Sparse Matrix File-
			<span style="color:blue; font-size:18px; font-weight: bold;">
				<?php echo  $FileNameOnly_final;?>
			</span>
		    <br />
		    <br />
		    <input type="submit" value="Run Train Command" name="frmSubmit" class="btn btn-primary">
		    <input type="submit" value="Run Automated Classification" name="frmSubmitAuto" class="btn btn-primary">
		</form>
	</div>
</body>
</html>
