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
    <link rel="stylesheet" href="css/contact.css">
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
                  <li><a href="index.php">Home</a></li>
                  <li class="active"><a href="#">Contact</a></li>
                  <?php if (isUserLoggedIn()) { echo '<li><a href="dashboard.php">Dashboard</a></li>'; } else { echo '<li><a href="login.php">Log in</a></li>'; } ?>
                </ul>
              </nav>
            </div>
            <div class="inner cover">
              <form class="form-contact">
                <h2 class="form-contact-heading">Contact me!</h2>
                <input type="text" id="name" class="form-control" placeholder="Name" required autofocus>
                <input type="email" id="inputEmail" class="form-control" placeholder="Email address" required>
                <input type="text" id="subject" class="form-control" placeholder="Subject" required autofocus>
                <textarea rows="8" cols="70" id="text" class="form-control" placeholder="Message"></textarea>
                <button class="btn btn-lg btn-primary btn-block" id="sendEmail" type="submit">Send</button>
                <div class="form-signin alert hidden" id="contactAlert" role="alert"></div> 
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="js/contact.js"></script>
  </body>
</html>

