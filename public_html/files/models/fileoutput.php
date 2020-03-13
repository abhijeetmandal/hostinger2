<?php
$target_dir = "../../../files/uploads/models/";
//echo ". ".realpath(".")."<br/>";
//echo "target_dir ".realpath("../../files/uploads/models/")."<br/>";
$sanitized = preg_replace('/[^a-zA-Z0-9\-\._]/','', $_GET['filename']);
$file = $target_dir . basename($sanitized);
//echo "file ".$file;

echo file_get_contents($file);
//echo "hello: ".$_GET['filename'];
//echo realpath(".");
?> 