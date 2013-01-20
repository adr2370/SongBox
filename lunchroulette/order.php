<?php 
  require 'includes/Ordrin/OrdrinApi.php';

  $ordrin = new OrdrinApi("Xm2hXohfPQQdn0WeyUGDyTV_t6xh1WWzIG-feAvOZt8", OrdrinApi::TEST_SERVERS);

  $address = $ordrin::address( $_GET['addr'], $_GET['city'], $_GET['state'], $_GET['zip'], 7187533087);
  $creditcard = $ordrin::creditCard( "Ricky Robinett", "04", "2016", "4111111111111111", 123, $address);
  $tray = $ordrin::tray( array() );
  foreach( $_GET['item'] as $item ) {
    $trayItem = $ordrin::trayItem( $item, 1 );
    $tray->add( $trayItem );
  }

  $ordrin->order->submit($_GET['rid'], $tray, 2.00, 'ASAP', 'ricky.robinett@gmail.com', '', 'Ricky', 'Robinett', $address, $creditcard);

  
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Lunch Roulette</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
 <style type="text/css">
      body {
        padding-top: 20px;
        padding-bottom: 40px;
      }

      /* Custom container */
      .container-narrow {
        margin: 0 auto;
        max-width: 700px;
      }
      .container-narrow > hr {
        margin: 30px 0;
      }

      /* Main marketing message and sign up button */
      .jumbotron {
        margin: 60px 0;
        text-align: center;
      }
      .jumbotron h1 {
        font-size: 72px;
        line-height: 1;
      }
      .jumbotron h3 {
        margin-bottom: 0px;
        line-height: 30px;
      }
      .jumbotron .btn {
        font-size: 21px;
        padding: 14px 24px;
      }

      /* Supporting marketing content */
      .marketing {
        margin: 60px 0;
      }
      .marketing p + h4 {
        margin-top: 28px;
      }
    </style>
    <link href="bootstrap/css/bootstrap-responsive.css" rel="stylesheet">

  </head>
  <body>
    <div class="container-narrow">

      <div class="masthead">
        <h3 class="muted">Lunch Roulette</h3>
      </div>

      <hr>

      <div class="jumbotron">
        <h1>Thanks for your order!</h1>
      </div>
      <hr>
      <div class="footer">
        <p>&copy; Ricky Robinett 2012</p>
      </div>
    </div>
  </body>
</html>
