<?php 
require_once '../../models/HelloWorld.class.php';

use atoum;

/*
 * Test class for Vendor\Project\HelloWorld
 *
 * Note that they had the same name that the tested class
 * and that it derives frim the atoum class
 */
class HelloWorld extends atoum
{
    /*
     * This method is dedicated to the getHiAtoum() method
     */
    public function testGetHiAtoum ()
    {
      $this->given($this->newTestedInstance)->string($this->testedInstance->getHiAtoum())->isEqualTo('Hello');       
    }
}
