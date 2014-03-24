
<!-- ======================================= -->
<!-- (HTML_Fragment) views/cart/cartTable.php -->

<div id="YourCartHas_NProducts">
	
	<?php
	if ($mode == 'checkout')
	{
		echo "<h3>Your Cart</h3>";
	}
	else
	{
		echo "<h3>Your Cart</h3>";
	}
	?>
	(<?php echo $cartLinesCount; ?> Product Lines, <?php echo $cartItemsTotal; ?> items in total)

</div>

<br />

<table id="cartTable" border="1">
	<tr>
		<th>
			<div class="itemColumn colheading">
				Item
			</div>
		</th>
		<th>
			<div class="qtyColumn colheading">
				Quantity
			</div>
		</th>
		<th>
			<div class="unitPriceColumn colheading">
				Unit Price
			</div>
		</th>
		<th>
			<div class="subTotColumn colheading">
				Sub Total
			</div>
		</th>
		<th>
			<!-- Column has buttons -->
		</th>
	</tr>

	<?php
	
	foreach ($cart as $cartItem)
	{
		?>
		<tr <?php echo 'id="cart_table_row_prod'.$cartItem['ProductID'].'"'?>>
			<td align="center" class="small_Text">
				
				<?php
				echo $cartItem['ProductName'];
				//echo " (id:" . $cartItem['ProductID'] . ")";
				?>
				
				<br />
				
				<?php
				echo img($PATH_TO_IMAGES.$PRODUCT_THUMBS . $cartItem['imgURL']);
				?>
				
			</td>
			
			
			
			
			<td>
				<?php
				
				// Quantity Input field
				
				$inputClass = "qty_inputClassCART";
				
				echo '<div class="cart_qty_Cell">';

					$attribs = array
					(
						'name'		=> 'quantity',
						'class'		=> $inputClass,
						'id'		=> 'qty_input_' . $cartItem['ProductID'],
						'value'		=> $cartItem['qty'],
						'maxlength' => $max_len_qty_field,
						'onchange'	=> 'updateCartItemQty(this);'
					);
					echo form_input($attribs);
					
				echo '</div>';
				?>
			</td>
			
			
			<td>
				<div class="cart_numeric_values">
					<?php

					// Unit Price

					echo '$ ' . $cartItem['unitPrice'];
					?>
				</div>
			</td>
			
		
			<td>
				<div id="subTot_prod<?php echo $cartItem['ProductID'];?>" class="cart_numeric_values">
					<?php

					// SubTotal

					$subTotal = $cartItem['unitPrice'] * $cartItem['qty'];

					// $grandTot = $grandTot + $subTotal;

					echo '$ ' . $subTotal;
					?>
				</div>
			</td>
			
			
			<td>
				<?php
				
				$buttonText = "Remove from Cart";
				
				if ($mode == 'checkout')
				{
					$buttonText = "Remove"; // shorter text
				}
				$attribs = array
				(
					'class'		=> 'rem_btn',
					'type'		=> 'button',
					'id'		=> 'btn_rem_' . $cartItem['ProductID'],
					'value'		=> $buttonText,
					'onclick'	=> "removeCartItem(this);"
				);
				echo form_input($attribs);
				?>
			</td>
		</tr>
		<?php
	}
	?>
</table>

<div id="grandTotalContainer" class="grandTotalLabel">
	
	Grand Total:
	
	<div id="grandTotalValue" class="grandTotalLabel grandTotalValue">
		<?php echo '$ ' . $grandTot ?>
	</div>
</div>

<!-- (END HTML_Fragment) views/cart/cartTable.php -->
