<?php include("../components/header.php"); ?>
<body>
	<?php include("../components/menu.php");?>
	<hr />
	<div class="col-md-12" style="font-size:12px;">
		<h4> WebSVM </h4>

		<p>
			WebSVM is web based implementation of popular SVM based library known as <a href="https://www.csie.ntu.edu.tw/~cjlin/libsvm/">LIBSVM<a/>. Besides core functionalities of LIBSVM, this site also allows other basic operations such as keyword extraction from text documents, weight calculation using TF-IDF and finally converting the feature file into sparse matrix format. This format can be used to train a model and test document classification.
		</p>

		<h4>What is SVM?</h4>
		<p>
			Support Vector Machine(SVM) is a supervised machine learning algorithm which can be used for both classification or regression challenges. However,  it is mostly used in classification problems. In this algorithm, we plot each data item as a point in n-dimensional space (where n is number of features you have) with the value of each feature being the value of a particular coordinate. Then, we perform classification by finding the hyper-plane that differentiate the two classes very well.
		</p>

		<a href="process.php" class="btn btn-primary btn-lg">Predict Using SVM</a>
	</div>
</body>
</html>
