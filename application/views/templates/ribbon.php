
<!-- ==================================== -->
<!-- (HTML_Fragment) View: Store (Ribbon) -->

<div class="row">
  
  <?php
  if (!isset($page_title))
  {
    $page_title = "";
  }
  ?>
  
  <?php
  $div_class = "ribbonLink";
  $div_id = "storeLink";
  echo anchor("store", "Store", 'id="storeLink" class="ribbonLink btn btn-primary" title="Online Store"');
  ?>
  
  <?php
  $div_class = "ribbonLink";
  $div_id = "cartLink";
  echo anchor("cart", "My Cart", 'id="cartLink" class="ribbonLink btn btn-primary" title="My Cart"');
  ?>

  <?php
  $div_class = "ribbonLink";
  $div_id = "checkoutLink";
  echo anchor("checkout/shippingMethod", "Checkout", 'id="checkoutLink" class="ribbonLink btn btn-primary" title="Checkout"');
  ?>

  <?php
  $div_class = "ribbonLink";
  $div_id = "myOrderstLink";
  echo anchor("notImplementedYet", "My Orders", 'id="myOrderstLink" class="ribbonLink btn btn-primary" title="My Orders"');
  ?>
  
</div>

<!-- (END HTML_Fragment) View: Store (Ribbon) -->