<?php
/**
 * @author Adrian Dvergsdal
 * @link http://github.com/atmoz/phiew
 * @license http://creativecommons.org/licenses/by-sa/3.0/us/
 */

class Phiew_View_Helper_InsertSelectFromArray
{
	/**
	 * Converts array to select-tag with values as options
	 * 
	 * @param array $array
	 * @param string $selected
	 * @param array $attributes
	 * @param string $dummy
	 */
	function insertSelectFromArray($array, $selected = null, $attributes = array(), $dummy = null)
    {
        $attrHtml = null;
        foreach ((array)$attributes as $arg => $val)
        {
            $attrHtml .= sprintf(' %s="%s"',
                htmlspecialchars($arg), htmlspecialchars($val)
            );
        }

        $result = "<select{$attrHtml}>";
       
        if ($dummy)
        {
            $result .= '<option value="">'.htmlspecialchars($dummy).'</option>';
        }

        foreach ((array)$array as $key => $value)
        {
            $selectedHtml = null;
            if ($key == $selected)
            {
                $selectedHtml = ' selected="selected"';
            }
            
            $result .= sprintf(
            	'<option value="%s"%s>%s</option>',
            	$key, $selectedHtml, htmlspecialchars($value)
            );
        }

        $result .= '</select>';
        return $result;
    }
}