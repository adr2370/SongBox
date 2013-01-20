<?php 
  require 'includes/Ordrin/OrdrinApi.php';
  $APIKEY = "Xm2hXohfPQQdn0WeyUGDyTV_t6xh1WWzIG-feAvOZt8";
  $ordrin = new OrdrinApi($APIKEY, OrdrinApi::TEST_SERVERS);

  $address = $ordrin::address( $_GET['addr'], $_GET['city'], $_GET['state'], $_GET['zip'], 7187533087);

  $dl = $ordrin->restaurant->getDeliveryList("ASAP", $address);
  $restaurant = $dl[ array_rand( $dl ) ];
  $rid = $restaurant->id;

  $rDetails = $ordrin->restaurant->details( $rid );
  $menu = $rDetails->menu;

  shuffle( $menu );
  $max = 35;//$_GET['amount'];
  $meal = build_meal( $menu, $max );


  function build_meal( $menu, $max ) {
    $filter_words = array( 'sides', 'appetizers', 'desserts', 'starters', 'catering' );
    $min_price = 8.50;

    $order_amount = 0;
    $order = array();
    foreach( $menu as $category ) {
      if( array_intersect( explode( " ", strtolower( $category->name ) ), $filter_words ) ) {
        continue;
      }
      shuffle( $category->children );

      foreach( $category->children as $menu_item ) {
        if( $menu_item->price < $min_price || ($order_amount + $menu_item->price) > $max ) {
          continue;
        }
        if( rand( 1, 5 ) === 5 ) {
          $order_amount += $menu_item->price;
          $order[] = array( "name" => $menu_item->name, "description" => $menu_item->descrip, "id" => $menu_item->id, 'price' => $menu_item->price );
        }
      }
      if( $order_amount >= $max * .9 ) {
        break;
      } 
    }
    if( empty( $order ) ) {
      return build_meal( $menu, $max );
    } else {
      return array( "order" => $order, "amount" => $order_amount );
    } 
  }
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
        <form id="form-order" action="order.php">
          <?php
            echo "Ordering from <b>". $restaurant->na . "</b> - <a href='#' onclick='location.reload();'>Try Again?</a><br/><br />";
            echo 'Your order:';
            foreach( $meal['order'] as $item ) {
              echo '<h3>' . $item['name'] . '</h3>' . $item['description'] . ' - ' . $item['price'] . '<br />';
              echo '<input type="hidden" name="item[]" value="'. $item['id'].'">';
            }
            echo '<br /><span style="font-size: 16px"><b>Amount: $' . $meal['amount']. "</b></span>";
          ?><br/><br />
          <input type="hidden" value="<?php echo $restaurant->id; ?>" name="rid">
          <input type="hidden" value="<?php echo $_GET['addr']; ?>" name="addr">
          <input type="hidden" value="<?php echo $_GET['city']; ?>" name="city">
          <input type="hidden" value="<?php echo $_GET['state']; ?>" name="state">
          <input type="hidden" value="<?php echo $_GET['zip']; ?>" name="zip">
          <button class="btn btn-large btn-primary" type="submit">Order</button>
        </form>
      </div>
      <hr>
      <div class="footer">
        <p>&copy; Ricky Robinett 2012</p>
      </div>
    </div>
  </body>
</html>
