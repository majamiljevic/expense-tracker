<?php 
  require("include/functions.php");
  if(isUserLoggedIn()){
    header("Location: dashboard.php");
  }
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
    <link rel="stylesheet" href="css/login.css">
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
                  <li><a href="contact.php">Contact</a></li>
                  <li class="active"><a href="login.php">Log in</a></li>
                </ul>
              </nav>
            </div>
            <div class="inner cover">
              <nav class="">
                  <ul class="nav nav-signin">
                    <li class="signin active"><h4 class="form-signin-heading">Sign in</h4></li>
                    <li class="signup"><h4 class="form-signup-heading">Sign up</h4></li>			  
                  </ul>
              </nav>
              <div class="form-signin" action="" id="form-signin">
                <input type="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
                <input type="password" id="inputPassword" class="form-control" placeholder="Password" required>
                <input type="text" id="inputFirstName" class="form-control hidden" placeholder="First name" required>
                <input type="text" id="inputLastName" class="form-control hidden" placeholder="Last name" required>
                <button class="btn btn-lg btn-primary btn-block" id="signin">Sign in</button>
                <button class="btn btn-lg btn-primary btn-block hidden" id="signup">Sign up</button>				
              </div>
              <div class="form-signin alert hidden" id="loginAlert" role="alert"></div>
            </div>
          </div>

        </div>
      </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="js/login.js"></script>
  </body>
</html>
