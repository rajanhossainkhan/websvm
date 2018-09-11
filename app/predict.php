<?php
	include("../components/header.php");

	//Globallly available environment variables
	include("../global.config.php");

	/**
	 * Read file directory and show the file to user
	 * At this moment, only one file is available
	 * Later, user based file management will be added
	 */
	$msg = "";
	$train_flag = 0;
	$dir  = $models_directory;
	$model_files = scandir($dir);


	//Grab model file
	$model_file = $model_files[3];



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
			$file_size =$_FILES['testFile']['size'];
			$file_tmp =$_FILES['testFile']['tmp_name'];
			$file_type=$_FILES['testFile']['type'];
			$file_ext=strtolower(end(explode('.',$_FILES['testFile']['name'])));

			$extensions= array("txt"); //Declare array for allowing more than one file type in future

			if(in_array($file_ext,$extensions)=== false){
				$error ="Extension not allowed, please choose a txt file to train your model.";
			}

			if($error == ""){
				move_uploaded_file($file_tmp,"../TestDatasets/".$file_name);
				echo "File is succesfully uploaded. <form method='post'><input type='submit' name='predictSubmit' class='btn btn-primary' value='Run Predict Command'></form>";
			}else{
				echo $error;
			}
		}
	}

	//Run predict command
	//Redirect to result page
	if (isset($_POST["predictSubmit"])){

		//Grab test file
		$test_files = scandir($test_dataset_directory);
		$test_file = $test_files[3];

		//Use generated model file in the previous step
		//Write the output file to OutputFiles directory
		shell_exec("../libsvm/./svm-predict " . $test_dataset_directory . $test_file ." ../LearningModels/". $model_file ." " . $output_directory . $training_file . ".output");
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
				<?php echo $model_file;?>
			</span>
		    <br />
		    <span>Upload Test Data Set (Sparse Matrix Format):</span><br />
		    <input type="file" name="testFile" class="form-control">
		    <br />
		    <input type="submit" value="Upload" name="frmSubmit" class="btn btn-primary">
		</form>
	</div>
</body>
</html>