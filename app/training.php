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
	$shell_output  = shell_exec("../libsvm/./svm-train " . $files_directory . $user_id_session . "/" . $TargetFile);
	if ($shell_output != ""){
		rename($TargetFile . ".model", "../LearningModels/{$user_id_session}/{$TargetFile}.model");
	}


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
		"UpdateBy" => $user_id_session,
		"reference_number" => $reference_number
	);
	$con->insert("UserFiles", $insert_array);

		$msg = "Classification is successfull. A result file is generated
	<a href='predict.php?TargetFile=".$TargetFile.".model&ref=".$reference_number."' class='btn btn-primary'> Proceed to Predict</a>";
}

$reference_number = "";
if (isset($_GET["ref"])){
	$reference_number = $_GET["ref"];
}


if (isset($_POST['frmSubmitAuto'])){


	$final_file_name = "";
	$fully_qualified_path = "";
	//See if test file was uploaded
	if (isset($_FILES['TestFileAuto'])){

		//Retrieve file information and save into db
		$file_name = $_FILES['TestFileAuto']['name'];
		$file_size =$_FILES['TestFileAuto']['size'];
		$file_tmp =$_FILES['TestFileAuto']['tmp_name'];
		$file_type=$_FILES['TestFileAuto']['type'];
		$file_ext=strtolower(end(explode('.',$_FILES['TestFileAuto']['name'])));

		$extensions= array("txt"); //Declare array for allowing more than one file type in future

		if(in_array($file_ext,$extensions)=== false){
			$error ="Extension not allowed, please choose a txt file to train your model.";
		}


		if($error == ""){

			//Create directory for the user if not yet created
			//Create a directory or check exisiting
			//Create directory for the user if not yet created
			$file_path_test = '../TestDatasets/' . $_SESSION['user_id'] . '/';
			if (!file_exists($file_path)) {
				mkdir($file_path_test, 0777, true);
			}

			$final_file_name = $reference_number . "_" . microtime(true)  . "_" .  $file_name;
			$fully_qualified_path = $file_path_test . "/" . $final_file_name;

			//Insert a record in the db for uploaded file for the user
			$insert_array = array(
				"FileNameGiven" => $trainingFileTitle,
				"FileName" => $final_file_name,
				"FileType" => "test",
				"FilePath" => $fully_qualified_path,
				"UserId" => $user_id_session,
				"UpdateDate" => date("Y-m-d H:i:s"),
				"UpdateBy" => $user_id_session,
				"reference_number" => $reference_number
			);
			if ($con->insert("UserFiles", $insert_array) == 1){
				//Upload file to target directory
				move_uploaded_file($file_tmp,$fully_qualified_path);
			} else {
				$err = "File upload failed.";
			}
		}else{

		}
	}

   	//Create a directory or check exisiting
	//Create directory for the user if not yet created
	$file_path = '../AutomaticFiles/' . $_SESSION['user_id'] . '/';
	if (!file_exists($file_path)) {
		mkdir($file_path, 0777, true);
	}


    /*
    *Generate GUID as reference number
    *All files generated in a single session of easy.py
    *are referenced by this number in db
    *A single process is identifiable this way with
    *training, testing, scaling, model and outfile files
    */
    $fail_flag = 0;
    $file_name_array = array("{$TargetFile}.model","{$TargetFile}.scale", "{$TargetFile}.range");
    foreach ($file_name_array as $item){
        $file_path_full = $file_path . $item;
       	$insert_array = array(
		    "FileNameGiven" => $trainingFileTitle,
		    "FileName" => $item,
		    "FileType" => "Auto",
		    "FilePath" => $file_path_full,
		    "UserId" => $user_id_session,
		    "UpdateDate" => date("Y-m-d H:i:s"),
		    "UpdateBy" => $user_id_session,
            "reference_number" => $reference_number
        );
        if ($con->insert("UserFiles", $insert_array) == 1){
            //Nothing happens
        } else {
            $fail_flag = 1;
        }
    }

    /**
	* Run easy.py command
	* Easy.py is now updated to support output location
	* If a test file was uploaded, that would be used to test classification
	* For no test file, training file would be used to predict
	* This is due to modification in the easy.py code
	* An idea is to add -loc as thir parameter and then
	* output location as a fourth parameter.
	* This is for later integration
	*/
	$target_path = $files_directory.$user_id_session."/".$TargetFile;
	$full_command = "../libsvm/tools/./easy.py ";
	if ($fully_qualified_path == ""){
		$full_command .= $target_path . " " . $target_path . " " . $file_path;
	} else {
		$full_command .= $target_path . " " . $fully_qualified_path . " " . $file_path;
	}

    //execute the command
	shell_exec($full_command);

	//Few files are generated in app directory because of grid.py
    //Originally easy.py does work without any such problem.
	//Move all these files to designated directory
	//Build the file names based on training filenames in the URL
	$cv_predict =  $TargetFile . ".predict"; //cv for cross validation
	$cv_scale = $TargetFile . ".scale";
	$cv_out = $TargetFile . ".scale.out";
	$cv_performance_vector = $TargetFile . ".scale.png"; // .png file is the measured performance vector

	//Test file predict and scale
	$cv_predict_test = $final_file_name . ".predict";
	$cv_scale_test = $final_file_name . ".scale";

	rename($cv_predict, "../AutomaticFiles/{$user_id_session}/{$cv_predict}"); //cv - cross validation
	rename($cv_scale, "../AutomaticFiles/{$user_id_session}/{$cv_scale}");
	rename($cv_out, "../AutomaticFiles/{$user_id_session}/{$cv_out}");
	rename($cv_performance_vector, "../AutomaticFiles/{$user_id_session}/{$cv_performance_vector}");

	//Move test file
	rename($cv_predict_test, "../AutomaticFiles/{$user_id_session}/{$cv_predict_test}");
	rename($cv_scale_test, "../AutomaticFiles/{$user_id_session}/{$cv_scale_test}");

	//Store these new entries pathnames in db as filetype - auto
	//Use the same reference number to track them

	$fail_flag =  0;
	$file_name_array = array($cv_predict, $cv_scale, $cv_out, $cv_performance_vector);
	foreach ($file_name_array as $item){
		$file_path_full = $file_path . $item;
       	$insert_array = array(
		    "FileNameGiven" => $trainingFileTitle,
		    "FileName" => $item,
		    "FileType" => "Auto",
		    "FilePath" => $file_path_full, //remove ../ from begining of file name
		    "UserId" => $user_id_session,
		    "UpdateDate" => date("Y-m-d H:i:s"),
		    "UpdateBy" => $user_id_session,
            "reference_number" => $reference_number
        );
        if ($con->insert("UserFiles", $insert_array) == 1){
            //Nothing happens
        } else {
            $fail_flag = 1;
        }
	}

    //Display message with reference number in link
	$msg = "Process is successfull. All required files are  generated.
		<a href='AutomaticFilesList.php?ref={$reference_number}' class='btn btn-primary'>Browse Output Files</a>";

}
?>

<body>
	<?php include("../components/menu.php");?>
	<hr />

	<div class="col-md-12" style="font-size:12px;">
		<?php echo $msg; ?>
		<form method="POST" enctype="multipart/form-data">
			Uploaded Sparse Matrix File-
			<span style="color:blue; font-size:18px; font-weight: bold;">
				<?php echo  $FileNameOnly_final;?>
			</span>

		    <br />
		    <br />

		    <input type="submit" value="Run Train Command" name="frmSubmit" class="btn btn-primary">
		    <input type="button" id="ShowTestFileUpload" value="Run Automated Classification" name="frmSubmitAuto" class="btn btn-primary">

		    <br />
		    <br />
		    <div style="display: none;" id="UploadTestFile">
		    	<span>* Test file will be used for prediction against the trained model. An accuracy rate will be generated at the end of the process. </span> <br /><br />
				<label>Upload Test File <b>[Optional]</b></label><br />


				<input type="file" name="TestFileAuto"/>
			</div>
			<br />
			 <input type="submit" value="Run Classification" name="frmSubmitAuto" class="btn btn-primary">
		</form>
	</div>
</body>
</html>

<script type="text/javascript">
	$(document).ready(function() {
		$("#ShowTestFileUpload").click(function(){
			$("#UploadTestFile").show();
		});
	});
</script>
