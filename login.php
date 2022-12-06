<?php
require("nav.php");
?>

<?php

$username = se($_POST, "username", "", false);
$password = se($_POST, "password", "", false);
$first = se($_POST, "first", "", false);
$last = se($_POST, "last", "", false);
if (isset($_POST["username"]) && isset($_POST["password"])) {
    $hasError = false;
    if (empty($username)) {
        flash("Username must not be empty", "danger");
        $hasError = true;
    }
    if (!preg_match('/^[a-z0-9_-]{3,30}$/i', $username)) {
        flash("Username must only be alphanumeric and can only contain - or _", "warning");
        $hasError = true;
    }
    if (empty($password)) {
        flash("password must not be empty", "danger");
        $hasError = true;
    }
    if (strlen($password) < 8) {
        flash("Password too short", "danger");
        $hasError = true;
    }
    if (!$hasError) {
        $db = getDB();
        $stmt = $db->prepare("SELECT id, username, password, first, last from Users where username = :username");
        try {
            $r = $stmt->execute([":username" => $username]);
            if ($r) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($user) {
                    $first = $user["first"];
                    $last = $user["last"];
                    $hash = $user["password"];
                    unset($user["password"]);
                    if (password_verify($password, $hash)) {
                        $_SESSION["user"] = $user;

                        die(header("Location: accounts.php"));
                    } else {
                        flash("Invalid password", "danger");
                    }
                } else {
                    flash("Username not found", "danger");
                }
            }
        } catch (Exception $e) {
            flash("<pre>" . var_export($e, true) . "</pre>");
        }
    }
}

?>
<div class="container-fluid">
    <h1>Login</h1>
    <form onsubmit="return validate(this)" method="POST">
        <div class="mb-3">
            <label class="form-label" for="username">Username</label>
            <input class="form-control" type="text" id="username" name="username" required value="<?php se($username); ?>" />
        </div>
        <div class="mb-3">
            <label class="form-label" for="pw">Password</label>
            <input class="form-control" type="password" id="pw" name="password" required minlength="8" />
        </div>
        <input type="submit" class="mt-3 btn btn-primary" value="Login" name="save"/>
    </form>
</div>
<script>
    function validate(form) {
        return true;
    }
</script>
<?php require_once("flash.php") ?>