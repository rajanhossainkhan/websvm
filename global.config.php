<?php
	//0 - off :: 1 - On
	error_reporting(1);


	$host_fulladdress = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$host = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";

	//Training file upload directory
	$files_directory = "../Uploads/TrainingFiles/";

	//Trained model file directory
	$models_directory = "../LearningModels/";

	//Test Dataset Directory
	$test_dataset_directory = "../TestDatasets/";

	//Prediction output directory
	$output_directory = "../OutputFiles/";
?>