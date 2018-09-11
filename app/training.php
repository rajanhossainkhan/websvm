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
	$dir    		= $files_directory;
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


		/**
		 * Assume uploaded file is a right svm file
		 * Run train command against it.
		 */
		shell_exec("../libsvm/./svm-train " . $files_directory . $training_file ." ../LearningModels/". $training_file .".model");

		//Check the directory if a model file is created
		//If so, operation was okay and display a message with a link to predict page
		$model_files = scandir("../LearningModels/");
		if (count($model_files) > 0){
			$train_flag = 1;
			$msg = "Training is successfull. A model file is generated
			<a href='predict.php' class='btn btn-primary'> Proceed to Predict</a>";
		}


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
				<?php echo $training_file;?>
			</span>
		    <br />
		    <br />
		    <input type="submit" value="Run Train Command" name="frmSubmit" class="btn btn-primary">
		</form>
	</div>
</body>
</html>