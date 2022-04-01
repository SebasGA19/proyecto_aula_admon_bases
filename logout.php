<?php

include_once "database.php";

session_start();
$_SESSION["id"] = null;
session_destroy();

js_redirect("/index.php");
