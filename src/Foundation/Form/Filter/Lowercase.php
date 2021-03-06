<?php
namespace Foundation\Form\Filter;

/**
 * Convert the value to all lowercase
 * 
 * @package Foundation\form\filter
 */
class Lowercase extends AbstractFilter
{

    public function filterValue($value)
    {
        return \strtolower($value);
    }
}
