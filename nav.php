<?php
session_start();
require("functions.php");
?>

<head>
    <title>Bank</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css" />
</head>
    
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <?php if (!is_logged_in()) : ?>
        <a class="navbar-brand" href="login.php">Bank</a>
    <?php else : ?>
        <a class="navbar-brand" href="accounts.php">Bank</a>
    <?php endif; ?>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navContent" aria-controls="navContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navContent">
        <div class="navbar-nav">
            <?php if (!is_logged_in()) : ?>
                <a class="nav-item nav-link" href="register.php">Register</a>
                <a class="nav-item nav-link" href="login.php">Login</a>
            <?php endif; ?>
            <?php if (is_logged_in()) : ?>
                <a class="nav-item nav-link" href="accounts.php">Accounts</a>
                <a class="nav-item nav-link" href="transactions.php">Transactions</a>
                <a class="nav-item nav-link" href="logout.php">Logout</a>
            <?php endif; ?>
        </div>
    </div>
</nav>