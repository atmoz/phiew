<?php
/**
 * @author Adrian Dvergsdal
 * @link http://github.com/atmoz/phiew
 * @license http://creativecommons.org/licenses/by-sa/3.0/us/
 */

class Phiew_View_Helper_InsertDateSelect
{
    /**
     * Too simple date select (will make it better later, using this as example for now)
     * 
     * @param string $name
     * @param array $value
     * @param string $format
     */
    public function insertDateSelect($name = null, $value = array(), 
        $format = '%s %s.%s.%s', $checkbox = false
    ) {
        $name   = htmlentities($name);
        $days   = range(1, 31);
        $months = range(1, 12);
        $years  = range(date('Y')-100, date('Y'));
        
        $dayHtml = '<select name="'.$name.'[day]" class="day">';
        foreach ($days as $day)
        {
            $selected = (isset($value['day']) && $value['day'] == $day) 
                      ? 'selected="selected"' : '';
            $dayHtml .= sprintf('<option value="%1$02d" %2$s>%1$02d</option>', $day, $selected);
        }
        $dayHtml .= '</select>';
        $monthHtml = '<select name="'.$name.'[month]" class="month">';
        foreach ($months as $month)
        {
            $selected = (isset($value['month']) && $value['month'] == $month)
                      ? 'selected="selected"' : '';
            $monthHtml .= sprintf('<option value="%1$02d" %2$s>%1$02d</option>', $month, $selected);
        }
        $monthHtml .= '</select>';
        $yearHtml = '<select name="'.$name.'[year]" class="year">';
        foreach ($years as $year)
        {
            $selected = (isset($value['year']) && $value['year'] == $year)
                      ? 'selected="selected"' : '';
            $yearHtml .= sprintf('<option value="%1$02d" %2$s>%1$02d</option>', $year, $selected);
        }
        $yearHtml .= '</select>';
        
        $toggleActiveHtml = '';
        if ($checkbox)
        {
            $checked = (!empty($value['active']) ? 'checked="checked"' : '');
            $toggleActiveHtml = '<input type="checkbox" name="'.$name.'[active]" '
                . 'value="1" '.$checked.' />';
        }
        else
        {
            $active = (int)(bool) (isset($value['active'])) ? $value['active'] : null;
            $toggleActiveHtml = '<input type="hidden" name="'.$name.'[active]" '
                . 'value="'.$active.'" />';
        }
        
        $html = '<span class="ViewHelper_DateTime"><input type="hidden" name="'.$name.'[dummy]" '
              . 'value="1" />' . sprintf($format, $toggleActiveHtml, $dayHtml, 
                $monthHtml, $yearHtml) . '</span>';
        
        echo $html;
    }
}