<?php
namespace Foundation\Form\Filter;

/**
 * Pull the file contents out and set them as the value
 * 
 * @package Foundation\form\filter
 */
class Blob extends AbstractFilter
{

    public function filterValue($value)
    {
        //some other filter might have preprocessed the file already
        if (!is_array($value)) {
            return $value;
        }
        if (array_key_exists('tmp_name', $value) and
            is_uploaded_file($value['tmp_name']) and
            $string = \file_get_contents($value['tmp_name'])
        ) {
            return $string;
        }

        return null; //failed to get any data from the file
    }
}
