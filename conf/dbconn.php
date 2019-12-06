<?php
$mysqli = mysqli_connect("localhost","root","","tvz") or die('Error connecting to MySQL server.');

// in order to display correctly country names such as Curaçao,
// the mysql connection needs to be defined as utf-8
$mysqli->query("SET NAMES 'utf8'");