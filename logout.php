<?php
session_start();
require("nav.php");
reset_session();

flash("Successfully logged out", "success");
header("Location: login.php");