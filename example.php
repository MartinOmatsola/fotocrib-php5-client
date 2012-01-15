<?php
require_once "Fotocrib.php";

$imgSrc = "http://fotocrib.com/images/lion.jpg";
$fileName = "lion";
$format = "png";

$client = new Fotocrib($imgSrc, $fileName, $format);

//repaints the image and stores it in a file called lion.png
$client->repaint(5, 44, 10);

//Change the source, filename and output format
$client->setImgSrc("http://fotocrib.com/images/lara.jpg");
$client->setFileName("lara");
$client->setFormat("jpg");
$client->roundCorners(44);


$client->setImgSrc("http://fotocrib.com/images/jubei.jpg");
$client->setFileName("jubei");
$client->setFormat("gif");
$client->cube(255, 255, 255);

?>
