
<h1>Phiew example</h1>
<h2>Phiew_TypeHint_Collection</h2>

<p>Using type hinting on a collection of values (ArrayObject)</p>

<?php

require_once 'autoload.php';

try
{
	$collection = new Phiew_TypeHint_Collection(array(
		'myArray'        => new Phiew_TypeHint('array'),
		'standardClass'  => new Phiew_TypeHint('stdClass', new stdClass()),
		'mixedTypes'     => new Phiew_TypeHint('string|array|boolean'),
		'regularValue'   => 'just a boring string, can be anything'
	));

	// Use the $collection as an ArrayObject
	$collection['myArray']       = array(1, 2, 3);
	$collection['standardClass'] = new stdClass();
	$collection['mixedTypes']    = 'foobar';
	$collection['mixedTypes']    = array();
	$collection['mixedTypes']    = true;
	$collection['regularValue']  = 'whatever';

	// Lets try to break a type hint rule (throws exception)
	$collection['mixedTypes'] = 123; // integer
}
catch (Phiew_TypeHint_WrongTypeException $e)
{
	echo '<p style="color:red;">' . $e->getMessage() . '</p>';
}
