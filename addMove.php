<?php
	include "../variables.php";

	$blockName = $_POST['blockName'] or die("error: blockname is not defined.");

	$feldID = $_POST['submit'] or die("error: feldID is not defined.");

	$w1w1 = $_POST['w1w1'];
	$w1w2 = $_POST['w1w2'];
	$w1w3 = $_POST['w1w3'];
	$w1w4 = $_POST['w1w4'];
	$w1w5 = $_POST['w1w5'];

	$w2w1 = $_POST['w2w1'];
	$w2w2 = $_POST['w2w2'];
	$w2w3 = $_POST['w2w3'];
	$w2w4 = $_POST['w2w4'];
	$w2w5 = $_POST['w2w5'];

	$w3w1 = $_POST['w3w1'];
	$w3w2 = $_POST['w3w2'];
	$w3w3 = $_POST['w3w3'];
	$w3w4 = $_POST['w3w4'];
	$w3w5 = $_POST['w3w5'];

	$fw1 = $_POST['fw1'];
	$fw2 = $_POST['fw2'];
	$fw3 = $_POST['fw3'];
	$fw4 = $_POST['fw4'];
	$fw5 = $_POST['fw5'];
		
	$thisString = "field_$feldID=$w1w1,$w1w2,$w1w3,$w1w4,$w1w5/$w2w1,$w2w2,$w2w3,$w2w4,$w2w5/$w3w1,$w3w2,$w3w3,$w3w4,$w3w5/$fw1,$fw2,$fw3,$fw4,$fw5";


	$thisFile = fopen("blocks/$blockName", "a") or die("error: Unable to open file!");
	fwrite($thisFile, "$thisString\n");
	fclose($thisFile);

	header("location:showBlock.php?blockName=$blockName");
?>