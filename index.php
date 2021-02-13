<?php
	include "../variables.php";

	$oldBlocks = scandir("blocks");

?>


<!DOCTYPE html>
<html>
	
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="stylesheet" href="style.css">
		<title>
			kniffel
		</title>
	</head>
	
	<body>
		<div class="seitenrand" >
			
			<h1 align="center"><?php echo $serverTitle;?> kniffel</h1>
		
			<div class='ugga_box' >
			
				<h2> <a href="createBlock.php">create new block</a> </h2>
			</div>
		
		
			<div class='ugga_box' >
				<?php
					for ($a = 2; $a < count($oldBlocks); $a++)
					{
						$filename = $oldBlocks[$a];
						echo "<a href='showBlock.php?blockName=$filename'>$filename</a><br>";
					}
				?>
			</div>	
			
		</div>
	</body>
</html> 

