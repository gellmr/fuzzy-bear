<?php

			
			class	Reply
			{

						private static $m_json;
						
						private static $html;
						
						private static $debugString;
						
						
						/**
						 * Constructor
						 */
						public	function	__construct()
						{
						}
						
						
						
						/**
						 * Clears all values to empty.
						 */
						public static function init()
						{
									self::$m_json = array();
									
									self::$html = "";
									
									self::$debugString = "";
						}
						
						
						
						
						/**
						 * Set a custom JSON name and value.
						 * 
						 * @param String $name
						 * @param String $val
						 */
						public static function setJsonArg($name = "", $val = "")
						{
									if (strlen($name) > 0)
									{
												self::$m_json[$name] = $val;
									}
						}
						
						
						/**
						 * Append more html.
						 * 
						 * @param String $str
						 */
						public static function addHTML($str = "")
						{
									self::$html .= $str;
						}
						
						
						
						
						/**
						 * Append a line to the debug string. Adds a <br />
						 * 
						 * @param String $str
						 */
						public static function writeLine_debug($str = "")
						{
									self::$debugString .= $str . "<br />";
						}
						
						
						/**
						 * Append a string to the debug string.
						 * 
						 * @param String $str
						 */
						public static function write_debug($str = "")
						{
									self::$debugString .= $str;
						}
						
						
						/**
						 * return all info as JSON ENCODED.
						 */
						public static function value()
						{
									self::$m_json["html"] = self::$html;
									
									self::$m_json["debugString"] = self::$debugString;
									
									return	json_encode
									(
												self::$m_json
									);
						}
			}

?>
