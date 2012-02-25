<?php
	require_once('/home/cominter/www/140dev/admin/includes/dbinit.php');
	    $results = $db->get_row("SELECT id, keywords FROM keywords where id = 3");
		define ('WORDS_TO_TRACK', "$results->keywords");
		//define (WORDS_TO_TRACK, 'disney');
?>	
	


