
<!-- ====================================== -->
<!-- (HTML_Fragment) View: Store Index Page -->

<?php
echo form_open('checkout');
?>

<div class="row">
  <div class="col-xs-12">
    <h3>
      Online Store
    </h3>
  </div>
</div>

<div class="row">

  <div class="col-xs-4">
    <b id='totProducts'>
      Total Products: <?php echo $prodCount; ?>
    </b>
  </div>
  
  <div class="col-xs-8">
    <div>
      Search for a Product
    </div>
  
    <div class="input-group">
      <?php // SEARCH BY KEYWORD
      $fieldName = 'input_searchBy_keyword';
      $attribs = array
      (
        'name'      => $fieldName,
        'id'        => $fieldName,
        'size'      => '11',
        'maxlength' => $max_search_string_len,
        'value'     => $key_word_value,
        'type'      => "text",
        'class'     => "form-control"
      );
      echo form_input($attribs);
      ?>
      
      <span class="input-group-btn">
        <?php // SEARCH BUTTON
        $attribs = array
        (
          'name'    => 'search_button',
          'id'    => 'search_button',
          'content' => 'Search',
          'type'  => 'button',
          'class' => 'btn btn-default'
        );
        echo form_button($attribs);
        ?>
      </span>
    </div>
  </div>
</div> <!-- row -->

<?php
// EXAMPLE BUTTON
// echo anchor(
//   '#',
//   'Hello Link',
//   'class="btn btn-default btn-primary"'
// );
?>

<div class="row" id="storeProducts_placeholder">
  <?php
  echo $storeProducts_html;
  ?>
</div>

<?php
echo form_close();
?>

<!-- (END HTML_Fragment) View: Store Index Page -->


