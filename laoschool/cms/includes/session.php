<?

function on_session_start($save_path, $session_name) {
	error_log($session_name . " ". session_id());
}

function on_session_end() {
	// Nothing needs to be done in this function
	// since we used persistent connection.
}

function on_session_read($key) {
	error_log($key);
	$stmt = "select session_data from sessions ";
	$stmt .= "where session_id ='$key' ";
	$stmt .= "and unix_timestamp(session_expiration) > unix_timestamp(date_add(now(),interval 1 hour))";
	$sth = mysql_query($stmt);

	if($sth)
	{
		$row = mysql_fetch_array($sth);
		return($row['session_data']);
	}
	else
	{
		return $sth;
	}
}
function on_session_write($key, $val) {
	error_log("$key = $value");
	$val = addslashes($val);
	$insert_stmt  = "insert into sessions values('$key', ";
	$insert_stmt .= "'$val',unix_timestamp(date_add(now(), interval 1 hour)))";

	$update_stmt  = "update sessions set session_data ='$val', ";
	$update_stmt .= "session_expiration = unix_timestamp(date_add(now(), interval 1 hour))";
	$update_stmt .= "where session_id ='$key '";
	
	// First we try to insert, if that doesn't succeed, it means
	// session is already in the table and we try to update
	
	
	mysql_query($insert_stmt);
	
	$err = mysql_error();
	
	if ($err != 0)
	{
		error_log( mysql_error());
		mysql_query($update_stmt);
	}
}

function on_session_destroy($key) {
	mysql_query("delete from sessions where session_id = '$key'");
}

function on_session_gc($max_lifetime) 
{
	mysql_query("delete from sessions where unix_timestamp(session_expiration) < unix_timestamp(now())");
}

    	    

//session_start();

?> 
