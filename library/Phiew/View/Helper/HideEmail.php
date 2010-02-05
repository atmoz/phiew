<?php
/**
 * @author Adrian Dvergsdal
 * @link http://github.com/atmoz/phiew
 * @license http://creativecommons.org/licenses/by-sa/3.0/us/
 */

class Phiew_View_Helper_HideEmail
{
	/**
	 * Hide email with JavaScript
	 * 
	 * @author Maurits van der Schee
	 * @link http://www.maurits.vdschee.nl/php_hide_email/
	 * @param string $email
	 * @return string
	 */
	public function hideEmail($email) 
	{ 
		$character_set = '+-.0123456789@ABCDEFGHIJKLMNOPQRSTUVWXYZ_abcdefghijklmnopqrstuvwxyz'; 
		$key = str_shuffle($character_set); $cipher_text = ''; $id = 'e'.rand(1,999999999); 
		for ($i=0;$i<strlen($email);
			$i+=1) $cipher_text.= $key[strpos($character_set,$email[$i])];
		$script = 'var a="'.$key.'";var b=a.split("").sort().join("");var c="'.$cipher_text.'";var d="";'; 
		$script.= 'for(var e=0;e<c.length;e++)d+=b.charAt(a.indexOf(c.charAt(e)));'; 
		$script.= 'document.getElementById("'.$id.'").innerHTML="<a href=\\"mailto:"+d+"\\">"+d+"</a>"'; 
		$script = "eval(\"".str_replace(array("\\",'"'),array("\\\\",'\"'), $script)."\")"; 
		$script = '<script type="text/javascript">/*<![CDATA[*/'.$script.'/*]]>*/</script>'; 
		return '<span id="'.$id.'">[javascript protected email address]</span>'.$script; 
	}
}
