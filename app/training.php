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

$reference_number = "";
if (isset($_GET["ref"])){
	$reference_number = $_GET["ref"];
}

if (isset($_POST['frmSubmitAuto'])){
    
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
	*/
	$target_path = $files_directory.$user_id_session."/".$TargetFile;
	$full_command = "../libsvm/tools/./easy.py ";
	$full_command .= $target_path . " " . $target_path . " " . $file_path;
     
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

	rename($cv_predict, "../AutomaticFiles/{$user_id_session}/{$cv_predict}"); //cv - cross validation
	rename($cv_scale, "../AutomaticFiles/{$user_id_session}/{$cv_scale}");
	rename($cv_out, "../AutomaticFiles/{$user_id_session}/{$cv_out}");
	rename($cv_performance_vector, "../AutomaticFiles/{$user_id_session}/{$cv_performance_vector}");

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
