<?php
function reset_session()
{
    session_unset();
    session_destroy();
    session_start();
}

function se($v, $k = null, $default = "", $isEcho = true)
{
    if (is_array($v) && isset($k) && isset($v[$k])) {
        $returnValue = $v[$k];
    } else if (is_object($v) && isset($k) && isset($v->$k)) {
        $returnValue = $v->$k;
    } else {
        $returnValue = $v;
        if (is_array($returnValue) || is_object($returnValue)) {
            $returnValue = $default;
        }
    }
    if (!isset($returnValue)) {
        $returnValue = $default;
    }
    if ($isEcho) {
        echo htmlspecialchars($returnValue, ENT_QUOTES);
    } else {
        return htmlspecialchars($returnValue, ENT_QUOTES);
    }
}

function flash($msg = "", $color = "info")
{
    $message = ["text" => $msg, "color" => $color];
    if (isset($_SESSION['flash'])) {
        array_push($_SESSION['flash'], $message);
    } else {
        $_SESSION['flash'] = array();
        array_push($_SESSION['flash'], $message);
    }
}

function getMessages()
{
    if (isset($_SESSION['flash'])) {
        $flashes = $_SESSION['flash'];
        $_SESSION['flash'] = array();
        return $flashes;
    }
    return array();
}

function getDB(){
    global $db;
    
    if(!isset($db)) {
        try{
            $ini = @parse_ini_file(".env");
            if($ini && isset($ini["DB_URL"])){
                $db_url = parse_url($ini["DB_URL"]);
            }
            else{
                $db_url = parse_url(getenv("DB_URL"));
            }
            $dbhost   = $db_url["host"];
            $dbuser = $db_url["user"];
            $dbpass = $db_url["pass"];
            $dbdatabase = substr($db_url["path"],1);

            $connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
            $db = new PDO($connection_string, $dbuser, $dbpass);
 	    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	}
   	catch(Exception $e){
            var_export($e);
            $db = null;
        }
    }
    return $db;
}

function users_check_duplicate($errorInfo)
{
    if ($errorInfo[1] === 1062) {
        preg_match("/Users.(\w+)/", $errorInfo[2], $matches);
        if (isset($matches[1])) {
            flash("The chosen " . $matches[1] . " is not available.", "warning");
        } else {
            flash("<pre>" . var_export($errorInfo, true) . "</pre>");
        }
    } else {
        flash("<pre>" . var_export($errorInfo, true) . "</pre>");
    }
}

function is_logged_in($redirect = false, $destination = "login.php")
{

    $isLoggedIn = isset($_SESSION["user"]);
    if ($redirect && !$isLoggedIn) {
        flash("You must be logged in to view this page", "warning");
        die(header("Location: $destination"));
    }
    return $isLoggedIn;
}

function get_accounts_count($id)
{
    $db = getDB();
    $stmt = $db->prepare("SELECT account_number, balance, from Accounts where user_id = :id AND active = TRUE");
    $stmt->execute([":id" => $id]);
    $accounts = $stmt->fetchAll(PDO::FETCH_OBJ);
    return sizeof($accounts);
}
