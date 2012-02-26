<?php
	require_once('/home/cominter/www/140dev/admin/includes/dbinit.php');

	echo "$_POST[keywords]";

	//$db->query("UPDATE keywords SET keywords='$_POST[keywords]' WHERE id='1')");
	//$db->query("update keywords set keywords = 'walt disney,wdw,lion king,pumba,anther one' where id=3");
	$db->query("update keywords set keywords = '$_POST[keywords]' where id=N");

?>