<?php if (!defined('BASEPATH')) exit('No direct script access allowed');




/**
 * Description of util
 *
 * @author Mike
 */
class Util
{
	
	
	public static function get_hiddenPNG_forAnchor()
	{
		$imageUrl = 'public/images/pagination_HIDDEN.png';

		// $imageUrl = 'public/images/paginationGREEN.png'; // Uncomment this to see the hidden image.

		return $imageUrl;
	}

	public static function bright_PNG_anchor($text, $cssClass = "", $divId="")
	{
		$html = '';

		$idAttrib = "";
		
		if (strlen($divId) > 0)
		{
			$idAttrib = "id=\"$divId\"";
		}
		
		$html .= '<div class="linkBright '.$cssClass.'" '.$idAttrib.'>';

		$html .= "<span>$text";

		$html .= '</span></div>';

		return $html;
	}

	public static function dark_PNG_anchor($controller, $text, $attribs, $cssClass = "", $divId="")
	{
		$html = '';
		
		$idAttrib = "";

		if (strlen($divId) > 0)
		{
			$idAttrib = "id=\"$divId\"";
		}
		
		$html .= '<div class="linkDark '.$cssClass.'" '.$idAttrib.'>';

		$html .= "<span>$text";

		$html .= anchor
		(
			$controller,

			img(self::get_hiddenPNG_forAnchor()),

			$attribs
		);

		$html .= '</span></div>';

		return $html;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	public function getDummyCart()
	{
		$dummyItemA = array
			(
			"ProductID" => 333,
			"ProductName" => "Apple",
			"qty" => 3,
			"unitPrice" => 30
		);
		$dummyItemB = array
			(
			"ProductID" => 444,
			"ProductName" => "Banana",
			"qty" => 4,
			"unitPrice" => 40
		);
		$dummyItemC = array
			(
			"ProductID" => 555,
			"ProductName" => "Carrot",
			"qty" => 5,
			"unitPrice" => 50
		);

		return array($dummyItemA, $dummyItemB, $dummyItemC);
	}

}

/* End of file Someclass.php */
?>