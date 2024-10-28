<?php

require 'util.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Generate Card</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
  <div class="container mt-5">
    <h2 class="text-center">Generate Card</h2>
    <form method="POST" action="card.php" class="mt-4">
      <div class="form-group">
        <label for="name">Name</label>
        <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
      </div>

      <div class="form-group">
        <label for="country_code">Country</label>
        <select class="form-control" id="country_code" name="country_code" required>
          <?php
              $countries_with_code = get_countries_with_code();
              foreach ($countries_with_code as $code => $name) {
                  $code = htmlentities($code);
                  $name = htmlentities($name);
                  echo "<option value=\"$code\">$name</option>";
              }
          ?>
        </select>
      </div>

      <div class="form-group">
        <label for="position">Position</label>
        <input type="text" class="form-control" id="position" name="position" placeholder="Enter your position" required>
      </div>

      <button type="submit" class="btn btn-primary btn-block">Generate Card</button>
    </form>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>