<?php
$sessionid = $_GET["sessionid"];
$clientid = ""; 
$connection = oci_connect("gq053", "tbirbo", "gqiannew3:1521/orc.uco.local");
if (!$connection) {
    die('Failed to connect to database');
} else { 
    // connection OK - validate current sessionid 
    if (!isset($sessionid) or ($sessionid=="")) { 
        // no session to maintain 
        $sessionid="";
    } else { 
        // lookup the sessionid in the session table to get the clientid 
        $sql = "SELECT username FROM UserSessionDatas WHERE sessionid=:sessionid"; 
        $cursor = oci_parse($connection, $sql);
        if($cursor == false){
            $e = oci_error($connection); 
            echo $e['message']."<BR>";
            // query failed - login impossible
            $sessionid="";
        } else { 
            oci_bind_by_name($cursor, ":sessionid", $sessionid);
            $result = oci_execute($cursor);
            if ($result == false){
                $e = oci_error($cursor); 
                echo $e['message']."<BR>";
                $sessionid="";
            } else {
                if($values = oci_fetch_array($cursor)){
                    // found the sessionid
                    $clientid = $values[0];
                } else { 
                    // invalid sessionid 
                    $sessionid = ""; 
                } 
            } 
            oci_free_statement($cursor);
        }
    } 
    oci_close($connection);
} 
?>

<!DOCTYPE html>
<html>
<head>
    <title>Check Session</title>
</head>
<body>
    <h1>Check Session</h1>
    <?php echo "Session ID: " . $sessionid; ?>
</body>
</html>
