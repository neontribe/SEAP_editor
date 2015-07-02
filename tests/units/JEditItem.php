<?php 
namespace SEAP_editor\tests\units;

require_once __DIR__ . '/../../JEditItem.class.php';

use atoum;

/*
 * Test class for SEAP_editor\JEditItem
 */
class JEditItem extends atoum
{
    /*
     * This method is dedicated to the cleanValue() method
     */
    //public function cleanValue()
    //{
      //$this
        //->given($this->newTestedInstance)
        //->string($this->testedInstance->cleanValue(' with lead and trail '))
        //->isEqualTo('with lead and tail')
      //;
    //}

  public function getHello() {
    $this->given($this->newTestedInstance)
      ->string($this->testedInstance->getHello())->isEqualTo('Hello');
  }
}
