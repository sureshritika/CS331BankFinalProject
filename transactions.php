<?php
require("nav.php");
?>

<script>
function validate(form) {
    return true;
}
</script>

<?php 
$selected_account = se($_POST, "select_account", "", false);

if (is_logged_in(true)) {
    echo "<div class='container-fluid'><h1>".$_SESSION["user"]["first"]."'s Transactions</h1></div>";

    $db = getDB();
    $stmt = $db->prepare("SELECT username, account_number from Accounts_Management where username = :username");
    $stmt->execute([":username" => $_SESSION["user"]["username"]]);
    $accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<div class="container-fluid">
    <form method="POST" id="form" onsubmit="return validate(this);">
        <label for="select_account" class="form-label">Select account</label>
        <select class="form-select w-25" id="select_account" name="select_account" required>
            <option hidden value="">Choose account</option>
            <?php
                foreach ($accounts as $account)
                {
                    $account_no = $account["account_number"];
                    ?>
                        <option <?php if($selected_account == $account_no) echo 'selected="selected"'; ?> value="<?php echo $account_no; ?>"><?php echo $account_no;?></option>
                    <?php
                }
            ?>
        </select><br>
        <div>
            <button type="submit" name="transact" value="transact" class="btn btn-primary">Get Transactions</button>
        </div>
    </form>
</div>

<?php
if (is_logged_in(true)) {

    if(isset($_POST["transact"]))
    {
        $stmt2 = $db->prepare("SELECT SUM(amount) as balance from Transactions where account_no = :account_no and month(transaction_timestamp) <> month(now())");
        $stmt2->execute([":account_no" => $selected_account]);
        $balance_forward = $stmt2->fetch(PDO::FETCH_OBJ)->balance;
        if ($balance_forward == null)
            $balance_forward = 0;
        $forward_date = "".date("Y")."-".(date('m')-1)."-31";

        $stmt1 = $db->prepare("SELECT date(transaction_timestamp) as transact_date, type, amount from Transactions where account_no = :account_no and month(transaction_timestamp) = month(now())");
        $stmt1->execute([":account_no" => $selected_account]);
        $transactions = $stmt1->fetchAll(PDO::FETCH_OBJ);

        echo "<div class='container-fluid'><table class='table table-striped table-bordered' id='transactions'>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Transaction Code</th>
                        <th>Transaction Name</th>
                        <th>Debits</th>
                        <th>Credits</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody>";
                        echo "<tr id='transaction'>";
                            echo "<td>$forward_date</td>";
                            echo "<td><br>";
                            echo "<td>Balance Forward</td>";
                            echo "<td></td>";
                            echo "<td><br>";
                            echo "<td>$balance_forward</td>";
                            //echo "<td>".number_format((float)$balance_forward, 2, '.', '')."</td>";
                        echo "</tr>";
                        $balance = $balance_forward;
                        foreach ($transactions as $transaction) {
                            $date = $transaction->transact_date;                       
                            echo "<tr id='transaction'>";
                                echo "<td>$transaction->transact_date</td>";
                                $type = $transaction->type;
                                echo "<td>$type<br>";
                                if ($type == "SC")
                                    echo "<td>Service Charge</td>";
                                else if ($type == "WD")
                                    echo "<td>Withdrawal</td>";
                                else if ($type == "CD")
                                    echo "<td>Customer Deposit</td>";
                                $amt = number_format((float)abs($transaction->amount), 2, '.', '');
                                if ($type == "SC" || $type == "WD") {
                                    echo "<td>$amt</td>";
                                    echo "<td></td>";
                                }
                                else {
                                    echo "<td></td>";
                                    echo "<td>$amt<br>";
                                }
                                $balance += $transaction->amount;
                                echo "<td>".number_format((float)$balance, 2, '.', '')."</td>";
                            echo "</tr>";

                        }
                echo "</tbody> 
            </table></div>";
    }
}
?>

<?php require_once("flash.php") ?>