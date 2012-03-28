<?php

	/******************************
	*	KEYGEN SCRIPT
	******************************/

	// KEY FORMAT:
	// 8 CHARACTERS
	// L N N L L N L N
	// 4th Digit = (1st Digit * 3rd Digit) (one digit long [0-9])
	// 4th Letter ID = (1st Letter ID + 3rd Letter ID) (If above alphabet length, remove one 'alphabet' count)

	// Generate a new key which fits within the specification
	function generateKey()
	{

		// Setup the generatedKey var
		$generatedKey = null;


		// List of alphabetical characters
		$keyLetters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";


		// Create the key algorithm of letters
		// 4th Letter ID = (1st Letter ID + 3rd Letter ID)

		// Generate a random letter from $keyLetters [A-Z]
		$letterOne = substr($keyLetters, mt_rand(0, 25), 1);
		$letterThree = substr($keyLetters, mt_rand(0, 25), 1);

		// Retrieve the letter ids based on their position in the alphabet
		$letterOneID = strpos($keyLetters, $letterOne);
		$letterThreeID = strpos($keyLetters, $letterThree);

		// Create the value for the 4th letter
		$letterFourID = ($letterOneID + $letterThreeID);

		// If the letter ids go above the possible ids of the alphabet, remove 'one alphabet' count from the value
		if(($letterOneID + $letterThreeID) > 25)
		{
			$letterFourID -= 25;
		}


		// Create the key algorithm of digits
		// 4th Digit = (1st Digit * 3rd Digit) (one digit long [0-9])
		$digitOne = mt_rand(0, 9);
		$digitThree = mt_rand(0, 9);
		$digitFour = substr($digitOne * $digitThree, 0, 1);


		// Combine the generated key
		$generatedKey .= $keyLetters[$letterOneID];
		$generatedKey .= $digitOne;
		$generatedKey .= mt_rand(0, 9);
		$generatedKey .= substr($keyLetters, mt_rand(0, 25), 1);
		$generatedKey .= $keyLetters[$letterThreeID];
		$generatedKey .= $digitThree;
		$generatedKey .= $keyLetters[$letterFourID];
		$generatedKey .= $digitFour;


		// Return the resulting key
		return $generatedKey;
	}

	function validateKey($unvalidatedKey)
	{
		// Check the format of the key is valid [L N N L L N L N]
		if(preg_match('/[A-Z][0-9][0-9][A-Z][A-Z][0-9][A-Z][0-9]$/', $unvalidatedKey) == 1){

			// Check if the digits fit the algorithm [first digit * third digit = last digit (first digit only)]
			if($unvalidatedKey[7] == substr(($unvalidatedKey[1] * $unvalidatedKey[5]), 0, 1)){
				
				// Check if the letters fit the algorithm
				// 4th Letter ID = (1st Letter ID + 3rd Letter ID)


				$keyLetters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

				// Retrieve the letter ids based on their position in the alphabet
				$letterOneID = strpos($keyLetters, $unvalidatedKey[0]);
				$letterThreeID = strpos($keyLetters, $unvalidatedKey[4]);

				// Create the value of the fourth letter
				$letterFourID = ($letterOneID + $letterThreeID);


				// If the fourth letter value is larger than the length of the alphabet, remove 'one alphabet' count from the value
				if($letterFourID > 25)
				{
					$letterFourID -= 25;
				}


				// Check if the fourth letter of the key matches the algorithm's result
				if($keyLetters[$letterFourID] == $unvalidatedKey[6]){
					return true;

				}
				else{
					return false;
				}

			}
			else{
				return false;
			}

		}
		else{
			return false;
		}

	}

	// Generate a new key
	$generatedKey = generateKey();

	// Output the key onto the webpage
	echo "GENERATED KEY -- ".$generatedKey.PHP_EOL;

	// Output the key validation result onto the webpage
	echo validateKey($generatedKey);
?>