<?php
/**
 * RadioList element form control;
 */
 ?>
 <ol>
<?php foreach($element->getItems() as $id => $item){
  echo "<li><input type='radio'";
  if($element->getValue() == $item->getValue()){
    print ' checked="checked"';
  }
  echo ' value="' . $item->getValue() . '" name="' . $element->getName() . '" id="' . $element->getName() . "_{$id}\" />" .
      '<label for="' . $element->getName() . "_{$id}\">" . $item->getLabel() . '</label>' .
      '</li>';
}
?>
</ol>