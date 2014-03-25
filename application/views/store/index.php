
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
  <a class="btn btn-default btn-primary">Hello Link</a>
	
	<b id='totProducts'>
		Total Products: <?php echo $prodCount; ?>
	</b>
	
	<div id="div_searchByKeyword">
		<?php
		//
		//
		//
		// SEARCH BY KEYWORD

		$fieldName = 'input_searchBy_keyword';

		echo 'Search for a Product<br/>';

		$attribs = array
		(
			'name'		=> $fieldName,
			'id'		=> $fieldName,
			'size'		=> '11',
			'maxlength'	=> $max_search_string_len,
			'value'		=> $key_word_value
		);
		echo form_input($attribs);
		//
		//
		//
		?>
		<div id="div_search_button">
			<?php
			// SEARCH BUTTON
			$attribs = array
			(
				'name'		=> 'search_button',
				'id'		=> 'search_button',
				'content'	=> 'Search'
			);
			echo form_button($attribs);
			?>
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


