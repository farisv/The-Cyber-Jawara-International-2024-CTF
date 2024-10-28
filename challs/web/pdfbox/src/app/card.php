<?php

require 'vendor/autoload.php';
require 'util.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$name = htmlspecialchars($_POST['name']);
$country_code = htmlspecialchars($_POST['country_code']);
$position = htmlspecialchars($_POST['position']);
$country_name = get_country_name($country_code);

$options = new Options();
$dompdf = new Dompdf($options);

$html = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Country Card</title>
  <style type="text/css">
    .id-card {
      width: 500px;
      margin: 0 auto;
      font-family: Arial, sans-serif;
    }

    .details {
      padding: 10px;
      text-align: left;
    }

    .details h3 {
      margin: 0;
      font-size: 16px;
    }

    .details p {
      margin: 5px 0;
      font-size: 12px;
    }
  </style>
</head>
<body>

<div class="id-card">
  <div class="details">
    <h3>$name</h3>
    <p>Country: $country_name</p>
    <p>Position: $position</p>
  </div>
</div>

</body>
</html>

HTML;

// Load HTML content into DOMPDF
$dompdf->loadHtml($html);

// Set paper size and orientation
$dompdf->setPaper('A6', 'landscape');

// Render the PDF
$dompdf->render();

$canvas = $dompdf->getCanvas();

$image_path = get_country_image_path($country_code);

$w = $canvas->get_width();
$h = $canvas->get_height() / 2;

$canvas->image($image_path, 0, $h, $w, $h, '', '', '');

// Stream the generated PDF to the browser
$dompdf->stream('image.pdf', ['Attachment' => false]);

?>
