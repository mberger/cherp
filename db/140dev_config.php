<?php
/**
* 140dev_config.php
* Constants for the entire 140dev Twitter framework
* You MUST modify these to match your server setup when installing the framework
* 
* Latest copy of this code: http://140dev.com/free-twitter-api-source-code-library/
* @author Adam Green <140dev@gmail.com>
* @license GNU Public License
* @version BETA 0.10
*/

// Directory for db_config.php
// original - define('DB_CONFIG_DIR', '/var/www/html/140dev/db/');
//define('DB_CONFIG_DIR', '/Users/mberger/Documents/workspace/140dev/db/');
define('DB_CONFIG_DIR', '/testing-this-git-thing/cominter/public_html/140dev/db/');

// Server path for scripts within the framework to reference each other
// original - define('CODE_DIR', '/var/www/html/140dev/');
define('CODE_DIR', '/home/cominter/public_html/140dev/');

// External URL for Javascript code in browsers to call the framework with Ajax
define('AJAX_URL', 'http://internetinnovation.com/140dev/');

// Basic authorization settings for connecting to the Twitter streaming API
// Fill in the values for a valid Twitter account
define('STREAM_ACCOUNT', 'twitter_user');
define('STREAM_PASSWORD', 'twitter_user_password');

// MySQL time zone setting to normalize dates
define('TIME_ZONE','America/New_York');

// Settings for monitor_tweets.php
define('TWEET_ERROR_INTERVAL',10);
// Fill in the email address for error messages
define('TWEET_ERROR_ADDRESS','mberger@internetinnovation.com');
?>
