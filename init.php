<?php
require_once ('./vendor/autoload.php');
/**
 *
 */
 //use PhpOffice\PhpSpreadsheet\Reader\IReader;
 //use PhpOffice\PhpSpreadsheet\IOFactory;
try {
  $user = 'root';
  $pass = '';
  $db = new PDO('mysql:host=localhost;dbname=test', $user, $pass);
} catch (PDOException $e) {
  print "Error!: " . $e->getMessage() . "<br/>";
}
