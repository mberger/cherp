<?php
/**
* parse_tweets.php
* Populate the database with new tweet data from the json_cache table
* Latest copy of this code: http://140dev.com/free-twitter-api-source-code-library/
* @author Adam Green <140dev@gmail.com>
* @license GNU Public License
* @version BETA 0.10
*/
require_once ('./140dev_config.php');
require_once ('./db_lib.php');
$oDB = new db ();

// This should run continuously as a background process
while ( true ) {
	
	// Process all new tweets
	$query = 'SELECT cache_id, raw_tweet ' . 'FROM json_cache WHERE NOT parsed';
	$result = $oDB->select ( $query );
	while ( $row = mysqli_fetch_assoc ( $result ) ) {
		
		$cache_id = $row ['cache_id'];
		// Each JSON payload for a tweet from the API was stored in the database
		// by serializing it as text and saving it as base64 raw data
		$tweet_object = unserialize ( base64_decode ( $row ['raw_tweet'] ) );
		
		// Mark the tweet as having been parsed
		$oDB->update ( 'json_cache', 'parsed = true', 'cache_id = ' . $cache_id );
		
		// Gather tweet data from the JSON object
		// $oDB->escape() escapes ' and " characters, and blocks characters that
		// could be used in a SQL injection attempt
		$tweet_id = $tweet_object->id_str;
		// Altered from original per mailing list to not truncate RT's
		// $tweet_text = $oDB->escape($tweet_object->text);
		if (isset ( $tweet_object->retweeted_status )) {
			$tweet_text = $oDB->escape ( 'RT @' . $tweet_object->retweeted_status->user->screen_name ) . ': ' . $oDB->escape ( $tweet_object->retweeted_status->text );
		} else {
			
			$tweet_text = $oDB->escape ( $tweet_object->text );
		}
		$created_at = $oDB->date ( $tweet_object->created_at );
		if (isset ( $tweet_object->geo )) {
			$geo_lat = $tweet_object->geo->coordinates [0];
			$geo_long = $tweet_object->geo->coordinates [1];
		} else {
			$geo_lat = $geo_long = 0;
		}
		$user_object = $tweet_object->user;
		$user_id = $user_object->id_str;
		$screen_name = $oDB->escape ( $user_object->screen_name );
		$name = $oDB->escape ( $user_object->name );
		$profile_image_url = $user_object->profile_image_url;
		$entities = $tweet_object->entities;
		
		// Add a new user row or update an existing one
		$field_values = 'screen_name = "' . $screen_name . '", ' . 'profile_image_url = "' . $profile_image_url . '", ' . 'user_id = ' . $user_id . ', ' . 'name = "' . $name . '", ' . 'location = "' . $oDB->escape ( $user_object->location ) . '", ' . 'url = "' . $user_object->url . '", ' . 'description = "' . $oDB->escape ( $user_object->description ) . '", ' . 'created_at = "' . $oDB->date ( $user_object->created_at ) . '", ' . 'followers_count = ' . $user_object->followers_count . ', ' . 'friends_count = ' . $user_object->friends_count . ', ' . 'statuses_count = ' . $user_object->statuses_count . ', ' . 'time_zone = "' . $user_object->time_zone . '", ' . 'last_update = "' . $oDB->date ( $tweet_object->created_at ) . '"';
		
		if ($oDB->in_table ( 'users', 'user_id="' . $user_id . '"' )) {
			$oDB->update ( 'users', $field_values, 'user_id = "' . $user_id . '"' );
		} else {
			$oDB->insert ( 'users', $field_values );
		}
		
		// Add the new tweet
		// The streaming API sometimes sends duplicates,
		// so test the tweet_id before inserting
		if (! $oDB->in_table ( 'tweets', 'tweet_id=' . $tweet_id )) {
			
			// The entities JSON object is saved with the tweet
			// so it can be parsed later when the tweet text needs to be
			// linkified
			$field_values = 'tweet_id = ' . $tweet_id . ', ' . 'tweet_text = "' . $tweet_text . '", ' . 'created_at = "' . $created_at . '", ' . 'geo_lat = ' . $geo_lat . ', ' . 'geo_long = ' . $geo_long . ', ' . 'user_id = ' . $user_id . ', ' . 'screen_name = "' . $screen_name . '", ' . 'name = "' . $name . '", ' . 'entities ="' . base64_encode ( serialize ( $entities ) ) . '", ' . 'profile_image_url = "' . $profile_image_url . '"';
			
			$oDB->insert ( 'tweets', $field_values );
		}
		
		// The mentions, tags, and URLs from the entities object are also
		// parsed into separate tables so they can be data mined later
		foreach ( $entities->user_mentions as $user_mention ) {
			
			$where = 'tweet_id=' . $tweet_id . ' ' . 'AND source_user_id=' . $user_id . ' ' . 'AND target_user_id=' . $user_mention->id;
			
			if (! $oDB->in_table ( 'tweet_mentions', $where )) {
				
				$field_values = 'tweet_id=' . $tweet_id . ', ' . 'source_user_id=' . $user_id . ', ' . 'target_user_id=' . $user_mention->id;
				
				$oDB->insert ( 'tweet_mentions', $field_values );
			}
		}
		foreach ( $entities->hashtags as $hashtag ) {
			
			$where = 'tweet_id=' . $tweet_id . ' ' . 'AND tag="' . $hashtag->text . '"';
			
			if (! $oDB->in_table ( 'tweet_tags', $where )) {
				
				$field_values = 'tweet_id=' . $tweet_id . ', ' . 'tag="' . $hashtag->text . '"';
				
				$oDB->insert ( 'tweet_tags', $field_values );
			}
		}
		foreach ( $entities->urls as $url ) {
			
			if (empty ( $url->expanded_url )) {
				$url = $url->url;
			} else {
				$url = $url->expanded_url;
			}
			
			$where = 'tweet_id=' . $tweet_id . ' ' . 'AND url="' . $url . '"';
			
			if (! $oDB->in_table ( 'tweet_urls', $where )) {
				$field_values = 'tweet_id=' . $tweet_id . ', ' . 'url="' . $url . '"';
				
				$oDB->insert ( 'tweet_urls', $field_values );
			}
		}
	}
	
	// You can adjust the sleep interval to handle the tweet flow and
	// server load you experience
	sleep ( 10 );
}

?>