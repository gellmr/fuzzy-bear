
<!-- ======================================= -->
<!-- (HTML_Fragment) views/cart/index        -->

<div class="bottomPad15">

	<?php
	if ($cartLinesCount == 0)
	{
		?>
		<div id="cannotCheckout_noCart wowza_cartDiv">
		<?php
	}
	else
	{

		?>
		<div id="wowza_cartDiv">
		<?php
	}


		// If this is just a summary of the cart, display summary

		if ($mode == 'summary')
		{

			echo "<br />";

			if ($cartLinesCount > 0)
			{

				echo "<h3>Your Cart</h3>($cartLinesCount Product Lines, $cartItemsTotal items in total)  Total charge: $ " . $grandTot;

			} else
			{
				echo "<h3>Your cart is empty.</h3>";
			}
		}
		else
		{
			// not a summary. This is "detailed" or "checkout" mode.

			if ($cartLinesCount > 0)
			{
				//
				//
				//
				echo validation_errors();
				//
				// When we submit the cart we are taken to the checkout.
				// 
				echo form_open('checkout');
					//
					//
					//
					// Print the cart table.
					// Probably bad coding practice to load view from a view.

					$data['mode'] = $mode;

					$data['cart'] = $cart;

					$this->load->view('cart/cartTable', $data);

					if ($mode != 'checkout')
					{
						// Add the submit button.

						echo "<br />";

						echo "<div id='cart_proceedToCheckout_submit'>";

							$attribs = array
							(
								'name'	=> 'submit',
								'id'	=> 'cart_submit',
								'value' => 'Proceed to Checkout',
								'style' => 'font-size:20px; width:53%; text-align:center',
							);

							echo form_submit($attribs);

						echo "</div>";
					}
					//
					//
					//
				echo "</form>";
				//
				//
				//
				echo "<br />";
			}
			else
			{
				echo "<br />";

				echo "<h3>Your cart is empty.</h3>";
				echo "<br />";
				echo "<br />";
				echo "To choose items for purchase, try browsing through our " . anchor("store", "online store", 'title="online store"');
			}
		}
		?>
		<br />
		<br />

	</div>
</div>

<!-- (END HTML_Fragment) views/cart/index    -->


