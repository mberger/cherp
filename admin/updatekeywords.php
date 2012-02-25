<?php
	require_once('/home/cominter/www/viewer/shared/dbinit.php');
		//Get the keywords from the database
		$results = $db->get_row("SELECT id, keywords FROM keywords where id = 3");
		echo "The current keywords defined are: <br><br>";
		echo " $results->keywords";
		echo "<br /><br />";
		//Tell the system which keywords to include below.
        // Original - define ('WORDS_TO_TRACK', "disney,wdw,lion king");
		//define ('WORDS_TO_TRACK', "$results->keywords");



echo "<hr width=50% align=left>";
echo "<h4>Keyword Form</h4>";
echo "<form action=processkeywords.php method=post> ";
echo "<textarea name=keywords cols=100 rows=20> $results->keywords </textarea>";
echo "<input type=submit />";
echo "</form>";


?>