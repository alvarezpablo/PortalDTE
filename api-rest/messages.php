<?php

include('config/config.php');
// Takes raw data from the request
$datos = $_REQUEST;

$json = file_get_contents('php://input');

// Converts it into a PHP object
$data = json_decode($json);

print_r($_SERVER);
print ("<br>post: ");
print_r($_POST);
print ("<br>get: ");
print_r($_GET);
print ("<br>request: ");
print($_REQUEST['sender']);
print ("<br>");
print($_REQUEST['attachment-count']);
print ("<br>files: ");
print_r($_FILES);
print ("<br>json: ");
print_r($data);
print ("<br>");

$fp = fopen('logs/upload.log', 'w');
fwrite($fp, $json);
fwrite($fp, "============================\n");
$req_dump = print_r($_REQUEST, TRUE);
fwrite($fp, $req_dump);
fwrite($fp, "============================\n");
fwrite($fp, $data);
fwrite($fp, "============================\n");
fwrite($fp, $_REQUEST['sender'] . "\n");
fwrite($fp, "============================\n");
fwrite($fp, $_REQUEST['attachment-count']);



$target_dir = "uploads/";

$nfile = $_REQUEST['attachment-count'];

$req = array (
    'sender'        => $_REQUEST['sender'],
    'recipient'     => $_REQUEST['recipient'],
    'Received'      => $_REQUEST['Received'],
    'From'          => $_REQUEST['From'],
    'Subject'       => $_REQUEST['Subject'],
    'Date'          => $_REQUEST['Date'],
    'Message-Id'    => $_REQUEST['Message-Id'],
    'body-plain'    => $_REQUEST['body-plain'],
    'attachment-count'    => $_REQUEST['attachment-count']

);

while ($nfile>0){
    $fname = "attachment-".$nfile;

    if ($_FILES[$fname]['type']=="application/xml"){
        fwrite($fp, "============================\n");    
        fwrite($fp, "Archivo: " . ($_FILES[$fname]['name']) . "\n");
        fwrite($fp, "Tipo: " . ($_FILES[$fname]['type']));

        

        $target_dir = $target_dir . basename( $_FILES[$fname]["name"]);

        $filename = $_FILES[$fname]["tmp_name"];

        $xmltext = fread(fopen($filename, "r"), filesize($filename));
        $xmlbase64 = base64_encode($xmltext);

        $att = array (  $fname => $fname,
                        'xmlbase64' => $xmlbase64
                    );

        if (move_uploaded_file($filename, $target_dir)) {
            fwrite($fp, "The file ". basename($filename). " has been uploaded.");
        } 

        $req = array_merge($req,$att);
    }
    $nfile = $nfile - 1;
}

try {

    $jsonReq = json_encode($req, JSON_FORCE_OBJECT);

    fwrite($fp, "============================\n");
    $rdump = print_r($jsonReq, TRUE);
    fwrite($fp, $rdump);

    fclose($fp);

    http_response_code(200);
}
catch (Exception $e){
    http_response_code(400);
}
?>

