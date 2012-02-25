<?php


  function random_row($table, $column) {

      $max_sql = "SELECT max(" . $column . ") 

                  AS max_id

                  FROM " . $table;

      $max_row = mysql_fetch_array(mysql_query($max_sql));

      $random_number = mt_rand(1, $max_row['max_id']);

      $random_sql = "SELECT * FROM " . $table . "

                     WHERE " . $column . " >= " . $random_number . " 

                     ORDER BY " . $column . " ASC

                     LIMIT 1";

      $random_row = mysql_fetch_row(mysql_query($random_sql));

      if (!is_array($random_row)) {

          $random_sql = "SELECT * FROM " . $table . "

                         WHERE " . $column . " < " . $random_number . " 

                         ORDER BY " . $column . " DESC

                         LIMIT 1";

          $random_row = mysql_fetch_row(mysql_query($random_sql));

      }

      return $random_row;

  }

  

  //USAGE

  echo '<pre>';

  print_r(random_row('YOUR_TABLE', 'YOUR_COLUMN'));

  echo '</pre>';

?>