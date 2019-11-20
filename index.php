<?php
ini_set('default_charset', 'utf-8');
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>CURZA - UNCo</title>
    <meta id="myViewport" name="viewport" content="width=device-width,user-scalable=no">
    <link rel="shortcut icon" href="build/favicon.ico">
    <link rel="stylesheet"
    href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
    integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr"
    crossorigin="anonymous"
    />
<?php

$debug = false;

$servername = getenv('DB_HOST');
$username = getenv('DB_USER');
$password = getenv('DB_PASS');
$dbname = getenv('DB_NAME');

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
   die("Connection failed: " . $conn->connect_error);
}

mysqli_set_charset($conn, 'utf-8');
$acentos = $conn->query("SET NAMES 'utf8'");

$actual_link = "https://".$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI];

if(substr($actual_link, -1) == '/'){
    $actual_link = substr($actual_link, 0, -1);
}

if($debug){
    echo "link: ".$actual_link."<br>";
}

$sql = "SELECT * FROM lnk_seo WHERE url = '".$actual_link."';";

if($debug){
    echo "sql: ".$sql."<br>";
}

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    if($debug){
        echo "data: ".print_r($row);
    }

    $metadata = json_decode($row['metadata'],true);

    $url = $row['url'];
    $type = $metadata['type'];
    $title = $metadata['title'];
    $description = $metadata['description'];
    $image = $metadata['image'];

} else {
    $url ="https://web.legisrn.gov.ar";
    $type = "website";
    $title = "Legislatura de Río Negro";
    $description = "Sitio de la Legislatura del Pueblo de la Provincia de Río Negro";
    $image = "https://webadmin.legisrn.gov.ar/assets/images/legisrn.jpg";
}

/* facebook */
echo '<meta property="og:url" content="'.$url.'" />';
echo '<meta property="og:type" content="'.$type.'" />';
echo '<meta property="og:title" content="'.$title.'" />';
echo '<meta property="og:description" content="'.$description.'" />';
echo '<meta property="og:image" content="'.$image.'" />';
echo '<meta property="fb:app_id" content="1062420063784365" />';
/* twitter */
echo '<meta name="twitter:card" content="summary">';
echo '<meta name="twitter:site" content="@legisrn">';
echo '<meta name="twitter:title" content="'.$title.'">';
echo '<meta name="twitter:description" content="'.$description.'">';
echo '<meta name="twitter:image" content="'.$image.'">';
/* google */
echo '<meta itemprop="name" content="'.$title.'">';
echo '<meta itemprop="description" content="'.$description.'">';
echo '<meta itemprop="image" content="'.$image.'">';


$conn->close();

?>
</head>
<body>
    <div id="legisrn-main"></div>
<?php if(!$debug){ ?>
    <script type="text/javascript" src="/bundle.js?v6"></script>
<?php } ?>
</body>
</html>
