<?php
error_reporting(0);
include('settings/class.inc.php');
$con = new Config();

require_once('textrazor/TextRazor.php');
$key = '6a15f0314923ca029632f28ce29011cefed1ff0ccead1d4e24ab9ba8';
TextRazorSettings::setApiKey($key);

$quartile = '';
$country = '';
$title = '';
$abstract = '';
$subject_areas = '';



if (isset($_POST['paper_title'])){
  $title = $_POST['paper_title'];
}
if (isset($_POST['paper_abstract'])){
  $abstract = $_POST['paper_abstract'];
}

if ($title == '' || $abstract == ''){
  $_SESSION['flash_message'] = '<br /><font color="red">Please copy and paste title and abstract from your manuscript.</font><br />';
  $con->redirect('index.php');
  exit();
}

if (isset($_POST['quartile'])){
  $quartile = $_POST['quartile'];
}
if (isset($_POST['country'])){
  $country = $_POST['country'];
}

if (isset($_POST['subject_areas']) && $_POST['subject_areas'] > 0){
  $subject_areas = base64_encode($_POST['subject_areas']);

  header("location: results.php?cid={$subject_areas}&quartile={$quartile}&country={$country}");
  exit();
}

$text = '';
$text = $title . ". ";
$text .= $abstract;

#transform text into small letter
$lowText = strtolower($text);

#analysze using texRazor
$textrazor = new TextRazor();
$textrazor->addExtractor('entities');
$response = $textrazor->analyze($lowText);

$total_element = count($response);

#Count total number of features with non-zero occurance
$val_count = 0;
if (isset($response['response']['entities'])) {
    foreach ($response['response']['entities'] as $entity) {
      $word = $entity['entityId'];
      $lowWord = strtolower($word);
      $count = substr_count($lowText, $lowWord);

      #id repeatance is not 0, then build the string as feature
      if ($count > 0){
        $val_count += 1;
      }
    }
}

#process keywords
$feature = 0;
$i = 1;
if (isset($response['response']['entities'])) {
    foreach ($response['response']['entities'] as $entity) {
        $word = $entity['entityId'];
        $lowWord = strtolower($word);
       	$count = substr_count($lowText, $lowWord);

       	#id repeatance is not 0, then build the string as feature
       	if ($count > 0){
	       	#calculate term frequency
	       	$tf = $count / $val_count;
	       	$frmt_tf = round($tf, 10);

	       	#idf (inverse document frequency)
	       	$idf = 1 + log(1 / $count);

	       	#tf-idf
	       	$tf_idf_raw = $frmt_tf * $idf;
	       	$tf_idf 	= round($tf_idf_raw, 10);
       		$feature .= " " . $i . ":" . $tf_idf;
       	}
       	$i++;
    }
}

$final_feature = $feature . "\n";

#write features in a file
file_put_contents("feature.txt", $final_feature);
include('analyze.php');


#navigate to machine learning module
$con->redirect('predict.php?quartile=' . $quartile . '&country=' . $country);

?>

