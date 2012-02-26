<?
	/**********************************************************************
	*  ezSQL initialisation for mySQL
	*/

	// Include ezSQL core
	include_once "/home/cominter/www/viewer/shared/ez_sql_core.php";

	// Include ezSQL database specific component
	include_once "/home/cominter/www/viewer/shared/ez_sql_mysql.php";

	// Initialise database object and establish a connection
	// at the same time - db_user / db_password / db_name / db_host
	$db = new ezSQL_mysql('db_user','db_password','db_name','db_host');

	/**********************************************************************
	*  ezSQL demo for mySQL database
	*/

?>