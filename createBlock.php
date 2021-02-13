<?php
	include "../variables.php";

	if( isset($_POST['createBlock']) )
	{
		$blockName = $_POST['blockName'];
		$games = $_POST['games'];
		
		$bonus_oben_limit = 63;
		$bonus_oben = 35;
		$fullHouse = 25;
		$smallStreet = 30;
		$largeStreet = 40;
		$kniffel = 50;
		
		if( file_exists ( "blocks/$blockName" ) )
		{	
			echo "error: a file with that name allready exists.";
			exit;
		}
		
		$newFile = fopen("blocks/$blockName", "w") or die("error: Unable to create new file!");
		
		fwrite($newFile, "games=$games\n");
		fwrite($newFile, "bonus_oben_limit=$bonus_oben_limit\n");
		fwrite($newFile, "bonus_oben=$bonus_oben\n");
		fwrite($newFile, "fullHouse=$fullHouse\n");
		fwrite($newFile, "smallStreet=$smallStreet\n");
		fwrite($newFile, "largeStreet=$largeStreet\n");
		fwrite($newFile, "kniffel=$kniffel\n");
		
		fclose($newFile);
			
		header("location:showBlock.php?blockName=$blockName");
	}
?>

<!DOCTYPE html>
<html>
	
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="stylesheet" href="style.css">
		<title>
			<?php echo $serverTitle;?> - create Kniffelblock
		</title>
	</head>
	<body>
		<form method="POST" action="createBlock.php" enctype="multipart/form-data">
    	<p>
			Enter name for this new block<br>
			<input type="text" name="blockName" required>
		</p>
		<p>
			How many rows schould the block have?<br>
			<input type="number" min="0" value="1" name="games">
		</p>
		<p>
    		<input type="submit" name="createBlock" value="create">
		</p>
		</form>
	</body>
</html>