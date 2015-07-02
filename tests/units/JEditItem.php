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
    public function testCleanValue()
    {
      $this
        ->given($this->newTestedInstance)
        ->string($this->testedInstance->cleanValue(' with lead and tail '))
        ->isEqualTo('with lead and tail')
      ;
      $this
        ->given($this->newTestedInstance)
        ->string($this->testedInstance->cleanValue('with tail '))
        ->isEqualTo('with tail')
      ;
      $this
        ->given($this->newTestedInstance)
        ->string($this->testedInstance->cleanValue('no lead nor tail'))
        ->isEqualTo('no lead nor tail')
      ;
    }
}
