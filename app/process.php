<?php include("../components/header.php"); ?>
<?php
	error_reporting(0);
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
		if(isset($_FILES['trainingFile'])){
			$errors = "";

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
				move_uploaded_file($file_tmp,"../Uploads/TrainingFiles/".$file_name);
				echo "File is succesfully uploaded. <a href='training.php' class='btn btn-primary'>Proceed to Training Page</a>";
			}else{
				echo $error;
			}
		}


	}
?>

<body>
	<?php include("../components/menu.php");?>
	<hr />
	<div class="col-md-12" style="font-size:12px;">
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