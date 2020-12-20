<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require '/var/www/iff/rusphp/src/PHP/Object/ObjectClass/Constructor.php';
require '/var/www/iff/rusphp/src/PHP/Object/ObjectFactory.php';

use ItForFree\rusphp\PHP\Object\ObjectClass\Constructor;
use ItForFree\rusphp\PHP\Object\ObjectFactory;

function debug($var = [], $dump = false, $die = false) {
    echo '<pre>';
    $dump === true ? var_dump($var) : print_r($var);
    echo '</pre>';
    
    if ($die === true) {
        die;
    }
}

class Before
{
}

class Testing
{
    public $before;
    public $a;
    public $b;
    
    public function __construct(Before $before, int $b, int $a = 1)
    {
        $this->before = $before;
        $this->a = $a;
        $this->b = $b;
    }
}

$objF = ObjectFactory::createObjectByConstruct('Testing', [
    3,
    'b' => 4,
    'before' => new Before
]);
