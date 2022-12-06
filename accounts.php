<?php require("nav.php")?>

<?php 
if (is_logged_in(true))
    echo "<div class='container-fluid'><h1>".$_SESSION["user"]["first"]."'s Accounts</h1></div>";
    
    $db = getDB();
    $stmt = $db->prepare("SELECT username, account_number from Accounts_Management where username = :username");
    $stmt->execute([":username" => $_SESSION["user"]["username"]]);
    $accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid">
    <table class="table" id="accounts">
        <thead>
            <tr>
                <th>Account number</th>
                <th>Balance</th>
                <th>Recent Access Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach ($accounts as $account) {
                    $account_no = $account["account_number"];
                    $stmt = $db->prepare("SELECT account_number, balance, date(recent_access_date) as recent_access_date from Accounts where account_number = :account_number");
                    $stmt->execute([":account_number" => $account_no]);
                    $account_info = $stmt->fetch(PDO::FETCH_OBJ);
                    
                    echo "<tr id='account'>";
                        echo "<td>$account_info->account_number</td>";
                        echo "<td>$account_info->balance<br>";
                        echo "<td>$account_info->recent_access_date</td>";
                    echo "</tr>"; 
                }
            ?> 
        </tbody> 
    </table>
</div>

<?php require_once("flash.php") ?>