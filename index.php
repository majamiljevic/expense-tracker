<?php 
  require("include/functions.php");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Money expense tracker features">
    <meta name="author" content="Maja Miljevic">
    <title>Simple expense tracking system!</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/index.css">
  </head>

  <body>
    <div class="site-wrapper">
      <div class="site-wrapper-inner">
        <div class="cover-container">
          <div class="masthead clearfix">
            <div class="inner">
              <h3 class="masthead-brand">Expense Tracker</h3>
              <nav>
                <ul class="nav masthead-nav">
                  <li class="active"><a href="#">Home</a></li>
                  <li><a href="contact.php">Contact</a></li>
                  <?php if (isUserLoggedIn()) { echo '<li><a href="dashboard.php">Dashboard</a></li>'; } else { echo '<li><a href="login.php">Log in</a></li>'; } ?>
                </ul>
              </nav>
            </div>
            <div class="jumbotron">
              <p class="lead">Simple way to see where you spend your money! <br> Worry less. Save money. </p>
            </div>
              <div class="row marketing">
                <div class="col-lg-6">
                  <h4>Simple!</h4>
                  <p>It’s easy to set up your free account in seconds</p>
                  <h4>Intuitive features</h4>
                  <p>All-round enough to help anyone’s money make sense without much effort.</p>
                </div>

                <div class="col-lg-6">
                  <h4>Mobile friendly</h4>
                  <p>Use it from your phone, too</p>
                  <h4>Bad habits?</h4>
                  <p>Do you really know how much your car costs you each year? Groceries? Find out and optimise your habits.</p>
                </div>
            </div>
            <br>
            <br>
            <h6>This is a diploma thesis project made by Maja Miljevic! Feel free to use it.</h6>
          </div>
 
        </div>
      </div>
    </div>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  </body>
</html>
