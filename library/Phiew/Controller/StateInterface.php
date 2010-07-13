<?php
/**
 * Phiew
 * - Simple and fast MVC components for PHP 5
 *
 * @version 0.12.0
 * @author Adrian Dvergsdal
 * @link http://github.com/atmoz/phiew
 * @license http://creativecommons.org/licenses/by-sa/3.0/us/
 */

/**
 * Controller state management
 */
interface Phiew_Controller_StateInterface
{
    public function loadState();
    public function saveState();
    public function getStateKey();
    public function getInstanceId();
    public function getData();
    public function setData(array $data);
    public function getDefaultData();
    public function setDefaultData(array $data);
}
