
<!-- ====================================== -->
<!-- (HTML_Fragment) View: Store Index Page -->

<div id="wowza_storeDiv">

	<?php
	echo form_open('checkout');
	?>
	
	<br />
	
	<h3>
		Online Store
	</h3>

  <br />
  <?php
  echo anchor(
    '#',
    'Hello Link',
    'class="btn btn-default btn-primary"'
  );
  ?>
	
	<b id='totProducts'>
		Total Products: <?php echo $prodCount; ?>
	</b>
	
  <div>
    Search for a Product
    <br />
    <div class="input-group">
  		<?php // SEARCH BY KEYWORD
  		$fieldName = 'input_searchBy_keyword';
  		$attribs = array
  		(
  			'name'		  => $fieldName,
  			'id'		    => $fieldName,
  			'size'		  => '11',
  			'maxlength'	=> $max_search_string_len,
  			'value'		  => $key_word_value,
        'type'      => "text",
        'class'     => "form-control"
  		);
  		echo form_input($attribs);
      ?>
  		
      <span class="input-group-btn">
  			<?php // SEARCH BUTTON
  			$attribs = array
  			(
  				'name'		=> 'search_button',
  				'id'		=> 'search_button',
  				'content'	=> 'Search',
          'type'  => 'button',
          'class' => 'btn btn-default'
  			);
  			echo form_button($attribs);
  			?>
      </span>
    </div>
	</div>

	
	<div id="storeProducts_placeholder">
		<?php
		echo $storeProducts_html;
		?>
	</div>
	
	<?php
	echo form_close();
	?>
	
</div>

<!-- (END HTML_Fragment) View: Store Index Page -->


