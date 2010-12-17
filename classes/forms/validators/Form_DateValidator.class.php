<?php
/**
 * Check to see if the input is a date
 * @author Jon Johnson <jon.johnson@ucsf.edu>
 * @license http://jazzee.org/license.txt
 * @package foundation
 * @subpackage forms
 */
class Form_DateValidator extends Form_Validator{
  public function validate(FormInput $input){
    if(!is_null($input->{$this->e->name}) AND !strtotime($input->{$this->e->name})){
      $this->addError('Not a valid date');
      return false;
    }
    return true;
  }
}
?>
