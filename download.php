<?php

if(!isset($_GET['course'])){
echo "Invalid Request";
exit();
}

$course = $_GET['course'];

$files = [

"html"=>"files/html_notes.pdf",
"css"=>"files/css_notes.pdf",
"js"=>"files/js_notes.pdf",
"python"=>"files/python_notes.pdf",

"python_source"=>"files/python_source.zip",
"java_source"=>"files/java_source.zip",
"login_system"=>"files/login_system.zip",
"portfolio"=>"files/portfolio.zip"

];

if(!array_key_exists($course,$files)){
echo "File not found";
exit();
}

$file = $files[$course];

header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="'.basename($file).'"');
readfile($file);
exit();

?>