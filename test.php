<?php


require_once(__DIR__."/config/db.php");

$query = DB::query("SELECT * FROM utilisateurs WHERE id = ? ",[1]);
$query->rowCount() > 0;