<?php
// Include necessary files
include_once '../sys/core/init.inc.php';
// Load the admin object

$obj = new Admin($dbo);

// Load a hash of the word test and output it
$hash1 = $obj->testSaltedHash("test");
echo "Hash 1 without a salt:<br />", $hash1, "<br /><br />";
// Pause execution for a second to get a different timestamp
sleep(1);
// Load a second hash of the word test
$hash2 = $obj->testSaltedHash("test");
echo "Hash 2 without a salt:<br />", $hash2, "<br /><br />";
// Pause execution for a second to get a different timestamp
sleep(1);
// Rehash the word test with the existing salt
$hash3 = $obj->testSaltedHash("test", $hash2);
echo "Hash 3 with the salt from hash 2:<br />", $hash3, "<br /><br />";

//hash of Admin
$adminHash = $obj->testSaltedHash("admin");
echo "Sha1 of admin: <br />" . sha1("admin"), "<br /><br />";
echo "Hash of admin: <br />" . $adminHash, "<br /><br />";