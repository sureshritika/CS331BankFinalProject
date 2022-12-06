<?php
require("nav.php");
reset_session();
?>

<?php
$first = se($_POST, "first", "", false);
$last = se($_POST, "last", "", false);
$username = se($_POST, "username", "", false);
$password = se($_POST, "password", "", false);
$confirm = se($_POST, "confirm", "", false);
?>

<div class="container-fluid">
    <h1>Register</h1>
    <form onsubmit="return validate(this)" method="POST">
        <div class="mb-3">
            <label class="form-label" for="username">First name</label>
            <input class="form-control" type="text" name="first" required value="<?php se($first); ?>" />
        </div>
        <div class="mb-3">
            <label class="form-label" for="username">Last name</label>
            <input class="form-control" type="text" name="last" required value="<?php se($last); ?>" />
        </div>
        <div class="mb-3">
            <label class="form-label" for="username">Username</label>
            <input class="form-control" type="text" name="username" required maxlength="30" required value="<?php se($username); ?>" />
        </div>
        <div class="mb-3">
            <label class="form-label" for="pw">Password</label>
            <input class="form-control" type="password" id="pw" name="password" required minlength="8" />
        </div>
        <div class="mb-3">
            <label class="form-label" for="confirm">Confirm</label>
            <input class="form-control" type="password" name="confirm" required minlength="8" />
        </div>
        <input type="submit" class="mt-3 btn btn-primary" value="Register" />
    </form>
</div>
<script>
    function validate(form) {
        return true;
    }
</script>
<?php
if (isset($_POST["first"]) && isset($_POST["last"]) && isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["confirm"])) {
    $hasError = false;
    if (empty($first)) {
        flash("First name must not be empty", "danger");
        $hasError = true;
    }
    if (!preg_match('/^[a-zA-Z]{1,}$/i', $first)) {
        flash("First name must only be alpha", "danger");
        $hasError = true;
    }
    if (empty($last)) {
        flash("Last name must not be empty", "danger");
        $hasError = true;
    }
    if (!preg_match('/^[a-zA-Z]{1,}$/i', $last)) {
        flash("Last name must only be alpha", "danger");
        $hasError = true;
    }
    if (!preg_match('/^[a-z0-9_-]{3,16}$/i', $username)) {
        flash("Username must only be alphanumeric and can only contain - or _", "danger");
        $hasError = true;
    }
    if (empty($password)) {
        flash("Password must not be empty", "danger");
        $hasError = true;
    }
    if (empty($confirm)) {
        flash("Confirm password must not be empty", "danger");
        $hasError = true;
    }
    if (strlen($password) < 8) {
        flash("Password too short", "danger");
        $hasError = true;
    }
    if (strlen($password) > 0 && $password !== $confirm) {
        flash("Passwords must match", "danger");
        $hasError = true;
    }
    if (!$hasError) {
        //TODO 4
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO Users (username, password, first, last) VALUES(:username, :password, :first, :last)");
        try {
            $stmt->execute([":username" => $username, ":password" => $hash, ":first" => $first, ":last" => $last]);
            flash("Successfully registered!");
        } catch (Exception $e) {
            users_check_duplicate($e->errorInfo);
        }
    }
}
?>
<?php require_once ("flash.php");?>