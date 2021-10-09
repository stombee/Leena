<?php


function convertEnum($array, $enum)
{
  $enums = [];
  foreach($array as $key => $value)
  {
    $enums[] = $enum::coerce($value);
  }
  return $enums;
}

function in_array_r($needle, $haystack, $strict = false)
{
  foreach ($haystack as $item) {
    if (($strict ? $item->key === $needle : $item->key == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
      return true;
    }
  }

  return false;
}


