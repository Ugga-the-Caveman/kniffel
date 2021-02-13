<?php
	include "../variables.php";

	$blockName = $_GET['blockName'] or die("error: blockname is not defined.");

	$thisFile = fopen("blocks/$blockName", "r") or die("error: Unable to open file!");

	$fieldArray = [];
	
	while ( !feof($thisFile) )
	{
		$thisLine = fgets($thisFile);
		
		if ( substr($thisLine, 0, 5) == "games" )
		{
			$games = (int) substr($thisLine, 6);
		}
		elseif ( substr($thisLine, 0, 16) == "bonus_oben_limit" )
		{
			$bonus_oben_limit = (int) substr($thisLine, 17);
		}
		elseif ( substr($thisLine, 0, 10) == "bonus_oben" )
		{
			$bonus_oben = (int) substr($thisLine, 11);
		}
		elseif ( substr($thisLine, 0, 9) == "fullHouse" )
		{
			$fullHouse = (int) substr($thisLine, 10);
		}
		elseif ( substr($thisLine, 0, 11) == "smallStreet" )
		{
			$smallStreet = (int) substr($thisLine, 12);
		}
		elseif ( substr($thisLine, 0, 11) == "largeStreet" )
		{
			$largeStreet = (int) substr($thisLine, 12);
		}
		elseif ( substr($thisLine, 0, 7) == "kniffel" )
		{
			$kniffel = (int) substr($thisLine, 8);
		}
		elseif ( substr($thisLine, 0, 6) == "field_" )
		{
			$start = strpos($thisLine, "c") + 1;
			$end = strpos($thisLine, "r") - $start;
			$col = (int) substr($thisLine, $start, $end);

			$start = strpos($thisLine, "r") + 1;
			$end = strpos($thisLine, "=") - $start;
			$row = (int) substr($thisLine, $start, $end);
			
			$thisArray = explode("/", substr($thisLine, 11));
			
			$newArray = [];
			
			foreach ($thisArray as $thisValue)
			{
				$newArray[] = (explode(",", $thisValue));
			}
			
			$fieldArray[$col][$row] = $newArray;
		}
	}
	fclose($thisFile);


	//echo '<pre>';
	//print_r($fieldArray);
	//echo '</pre>';


	$zwischensumme_oben_array = [];
	$summe_oben_array = [];
	$summe_unten_array = [];


	for ($i = 0; $i < $games; $i++)
	{
		$zwischensumme_oben_array[$i] = 0;
		$summe_oben_array[$i] = 0;
		$summe_unten_array[$i] = 0;
	}



	function getFieldScore($row, $fieldArray)
	{
		$score = 0;
		
		switch ($row)
		{
			//einser
    		case 0:
				foreach ($fieldArray as $thisValue)
				{
					if ($thisValue == 1)
					{
						$score = $score + 1;
					}
				}
        		break;

			//zweier
    		case 1:
        		foreach ($fieldArray as $thisValue)
				{
					if ($thisValue == 2)
					{
						$score = $score + 2;
					}
				}
        		break;

			//dreier		
    		case 2:
        		foreach ($fieldArray as $thisValue)
				{
					if ($thisValue == 3)
					{
						$score = $score + 3;
					}
				}
        		break;

			//vierer
			case 3:
        		foreach ($fieldArray as $thisValue)
				{
					if ($thisValue == 4)
					{
						$score = $score + 4;
					}
				}
        		break;

			//fünfer
			case 4:
        		foreach ($fieldArray as $thisValue)
				{
					if ($thisValue == 5)
					{
						$score = $score + 5;
					}
				}
        		break;

			//sechser
			case 5:
        		foreach ($fieldArray as $thisValue)
				{
					if ($thisValue == 6)
					{
						$score = $score + 6;
					}
				}
        		break;

			//zwischensumme oben
			//case 6:
        		//break;

			//bonus
			//case 7:
        		//break;

			//summe oben
			//case 8:
        		//break;

			//dreier pasch
			case 9:
				
				$countArray = [0,0,0,0,0];

				foreach ($fieldArray as $thisValue)
				{
					$score = $score + $thisValue;
					
					$thisIndex = $thisValue - 1;
					$countArray[$thisIndex] = $countArray[$thisIndex] + 1;
				}
				
				
				$isValid = false;
				
				foreach ($countArray as $dieCount)
				{
					if ($dieCount >= 3)
					{
						$isValid = true;
						break;
					}
				}
				
				if ($isValid == false)
				{
					$score = 0;
				}
				
        		break;

			//vierer pasch
			case 10:
				
				$countArray = [0,0,0,0,0];

				foreach ($fieldArray as $thisValue)
				{
					$score = $score + $thisValue;
					
					$thisIndex = $thisValue - 1;
					$countArray[$thisIndex] = $countArray[$thisIndex] + 1;
				}
				
				
				$isValid = false;
				
				foreach ($countArray as $dieCount)
				{
					if ($dieCount >= 4)
					{
						$isValid = true;
						break;
					}
				}
				
				if ($isValid == false)
				{
					$score = 0;
				}
				
        		break;

			//full house
			case 11:
				
				
				$countArray = [0,0,0,0,0];

				foreach ($fieldArray as $thisValue)
				{
					$score = $score + $thisValue;
					
					$thisIndex = $thisValue - 1;
					$countArray[$thisIndex] = $countArray[$thisIndex] + 1;
				}

				rsort($countArray);
				
				if ( $countArray[0] == 3 and $countArray[1] == 2 )
				{
					global $fullHouse;
					$score = $fullHouse;
				}
				
        		break;

			//kleine straße
			case 12:
				$countArray = [0,0,0,0,0];

				foreach ($fieldArray as $thisValue)
				{
					$thisIndex = $thisValue - 1;
					$countArray[$thisIndex] = $countArray[$thisIndex] + 1;
				}
				
				$missingDice = 0;
				
				foreach ($countArray as $dieCount)
				{
					if ($dieCount > 2)
					{
						$missingDice++;
					}
				}
				
				if ($missingDice <= 1)
				{
					global $smallStreet;
					$score = $smallStreet;
				}

        		break;

			//große straße
			case 13:
				sort($fieldArray);
				
				if ($fieldArray[0] == 1 and $fieldArray[1] == 2 and $fieldArray[2] == 3 and $fieldArray[3] == 4 and $fieldArray[4] == 5)
				{
					global $largeStreet;
					$score = $largeStreet;
				}
				elseif ($fieldArray[0] == 2 and $fieldArray[1] == 3 and $fieldArray[2] == 4 and $fieldArray[3] == 5 and $fieldArray[4] == 6)
				{
					global $largeStreet;
					$score = $largeStreet;
				}
        		break;
			
			//kniffel
			case 14:
				
				$firstValue = $fieldArray[0];
				
				if ($fieldArray[1] == $firstValue and $fieldArray[2] == $firstValue and $fieldArray[3] == $firstValue and $fieldArray[4] == $firstValue)
				{
					global $kniffel;
					$score = $kniffel;
				}
        		break;

			//chance
			case 15:
				foreach ($fieldArray as $thisValue)
				{
					$score = $score + $thisValue;
				}
        		break;
				
			//summe unten
			//case 16:
        		//break;
				
			//endsumme
			//case 17:
        		//break;
		}
		
		return $score;
	}


	function foo($collumn, $row)
	{
		echo "<td>";
		
		global $fieldArray;
		global $zwischensumme_oben_array;
		global $summe_unten_array;

		if ( !isset($fieldArray[$collumn][$row]) )
		{
			echo "<button type='submit' name='submit' value='c".$collumn."r".$row."'>_</button> ";
		}
		else
		{
			$thisArray = array_pop($fieldArray[$collumn][$row]);
			
			//print_r($thisArray);
			
			$thisScore = getFieldScore($row,$thisArray);
			echo $thisScore;
			
			if ($row <= 5)
			{
				$zwischensumme_oben_array[$collumn] = $zwischensumme_oben_array[$collumn] + $thisScore;
			}
			
			if ($row >= 9 and $row <= 15)
			{
				$summe_unten_array[$collumn] = $summe_unten_array[$collumn] + $thisScore;
			}
		}

		echo "</td>";
	}

	$rowindex = 0;
?>


<!DOCTYPE html>
<html>
	
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="stylesheet" href="style.css">
		<title>
			<?php echo $serverTitle;?> - Kniffelblock
		</title>
	</head>
	<body>
		
		<form method="POST" action="addMove.php" enctype="multipart/form-data">

		<p align="center">
			Erster Wurf
			<input type="number" size="1" min="1" max="6" name="w1w1">
			<input type="number" size="1" min="1" max="6" name="w1w2">
			<input type="number" size="1" min="1" max="6" name="w1w3">
			<input type="number" size="1" min="1" max="6" name="w1w4">
			<input type="number" size="1" min="1" max="6" name="w1w5">
		</p>
		<p align="center">
			Zweiter Wurf
			<input type="number" size="1" min="1" max="6" name="w2w1">
			<input type="number" size="1" min="1" max="6" name="w2w2">
			<input type="number" size="1" min="1" max="6" name="w2w3">
			<input type="number" size="1" min="1" max="6" name="w2w4">
			<input type="number" size="1" min="1" max="6" name="w2w5">
		</p>
		<p align="center">
			Dritter Wurf
			<input type="number" size="1" min="1" max="6" name="w3w1">
			<input type="number" size="1" min="1" max="6" name="w3w2">
			<input type="number" size="1" min="1" max="6" name="w3w3">
			<input type="number" size="1" min="1" max="6" name="w3w4">
			<input type="number" size="1" min="1" max="6" name="w3w5">
		</p>
		<p align="center">
			Final
			<input type="number" size="1" min="1" max="6" name="fw1" required>
			<input type="number" size="1" min="1" max="6" name="fw2" required>
			<input type="number" size="1" min="1" max="6" name="fw3" required>
			<input type="number" size="1" min="1" max="6" name="fw4" required>
			<input type="number" size="1" min="1" max="6" name="fw5" required>
		</p>

		<table border=1 align="center">
			<tr>
				<td colspan="2">
					
				</td>
				<?php for ($i = 0; $i < $games; $i++) {
						echo "<td>Spiel ".($i+1)."</td>";
				};?>
			</tr>
			<tr>
				<td>
					einser
				</td>
				<td>
					nur einser zählen
				</td>
				<?php 
					for ($i = 0; $i < $games; $i++) {
						foo($i,$rowindex);
					}
					$rowindex++;
				?>
			</tr>
			<tr>
				<td>
					zweier
				</td>
				<td>
					nur zweier zählen
				</td>
				<?php 
					for ($i = 0; $i < $games; $i++) {
						foo($i,$rowindex);
					}
					$rowindex++;
				?>
			</tr>
			<tr>
				<td>
					dreier
				</td>
				<td>
					nur dreier zählen
				</td>
				<?php 
					for ($i = 0; $i < $games; $i++) {
						foo($i,$rowindex);
					}
					$rowindex++;
				?>
			</tr>
			<tr>
				<td>
					vierer
				</td>
				<td>
					nur vierer zählen
				</td>
				<?php 
					for ($i = 0; $i < $games; $i++) {
						foo($i,$rowindex);
					}
					$rowindex++;
				?>
			</tr>
			<tr>
				<td>
					fünfer
				</td>
				<td>
					nur fünfer zählen
				</td>
				<?php 
					for ($i = 0; $i < $games; $i++) {
						foo($i,$rowindex);
					}
					$rowindex++;
				?>
			</tr>
			<tr>
				<td>
					sechser
				</td>
				<td>
					nur sechser zählen
				</td>
				<?php 
					for ($i = 0; $i < $games; $i++) {
						foo($i,$rowindex);
					}
					$rowindex++;
				?>
			</tr>
			<tr>
				<td colspan="2">
					Zwischensumme
				</td>
				<?php 
					for ($i = 0; $i < $games; $i++) {
						echo "<td>$zwischensumme_oben_array[$i]</td>";
					}
					$rowindex++;
				?>
			</tr>
			<tr>
				<td>
					<?php
						echo "Bonus (+".$bonus_oben_limit.")";
					?>
				</td>
				<td>
					<?php
						echo $bonus_oben;
					?>
				</td>
				<?php
					for ($i = 0; $i < $games; $i++)
					{
						if ($zwischensumme_oben_array[$i] >= $bonus_oben_limit)
						{
							echo "<td>$bonus_oben</td>";
						}
						else
						{
							echo "<td>0</td>";
						}
					}
					$rowindex++;
				?>
			</tr>
			<tr>
				<td colspan="2">
					Summe Oben
				</td>
				<?php
					for ($i = 0; $i < $games; $i++) {
						
						$summe = $zwischensumme_oben_array[$i];
						if ($summe >= $bonus_oben_limit)
						{
							$summe = $summe + $bonus_oben;
						}
						echo "<td>$summe</td>";
					}
					$rowindex++;
				?>
			</tr>
			<tr>
				<td>
					dreier Pasch
				</td>
				<td>
					alle Augen zählen
				</td>
				<?php 
					for ($i = 0; $i < $games; $i++) {
						foo($i,$rowindex);
					}
					$rowindex++;
				?>
			</tr>
			<tr>
				<td>
					vierer Pasch
				</td>
				<td>
					alle Augen zählen
				</td>
				<?php 
					for ($i = 0; $i < $games; $i++) {
						foo($i,$rowindex);
					}
					$rowindex++;
				?>
			</tr>
			<tr>
				<td>
					full House
				</td>
				<td>
					<?php
						echo $fullHouse." Punkte";
					?>
				</td>
				<?php 
					for ($i = 0; $i < $games; $i++) {
						foo($i,$rowindex);
					}
					$rowindex++;
				?>
			</tr>
			<tr>
				<td>
					kleine Straße
				</td>
				<td>
					<?php
						echo $smallStreet." Punkte";
					?>
				</td>
				<?php 
					for ($i = 0; $i < $games; $i++) {
						foo($i,$rowindex);
					}
					$rowindex++;
				?>
			</tr>
			<tr>
				<td>
					große Straße
				</td>
				<td>
					<?php
						echo $largeStreet." Punkte";
					?>
				</td>
				<?php 
					for ($i = 0; $i < $games; $i++) {
						foo($i,$rowindex);
					}
					$rowindex++;
				?>
			</tr>
			<tr>
				<td>
					Kniffel
				</td>
				<td>
					<?php
						echo $kniffel." Punkte";
					?>
				</td>
				<?php 
					for ($i = 0; $i < $games; $i++) {
						foo($i,$rowindex);
					}
					$rowindex++;
				?>
			</tr>
			<tr>
				<td>
					Chance
				</td>
				<td>
					alle Augen zählen
				</td>
				<?php 
					for ($i = 0; $i < $games; $i++) {
						foo($i,$rowindex);
					}
					$rowindex++;
				?>
			</tr>
			<tr>
				<td colspan="2">
					Summe Unten
				</td>
				<?php
					for ($i = 0; $i < $games; $i++) {
						
						echo "<td>$summe_unten_array[$i]</td>";
					}
					$rowindex++;
				?>
			</tr>
			<tr>
				<td colspan="2">
					Endsumme
				</td>
				<?php
					for ($i = 0; $i < $games; $i++) {
						
						$summe_oben = $zwischensumme_oben_array[$i];
						if ($summe_oben >= $bonus_oben_limit)
						{
							$summe_oben = $summe_oben + $bonus_oben;
						}
						
						$endsumme = $summe_oben + $summe_unten_array[$i];
						echo "<td>$endsumme</td>";
					}
					//$rowindex++;  not needed if this is the last row
				?>
			</tr>
		</table>
			
			<input type="hidden" name="blockName" value="<?php echo $blockName; ?>"/>
		</form>

	</body>
</html>
