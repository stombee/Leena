<?php

namespace Erenkucukersoftware\BrugsMigrationTool\Enums;

use BenSampo\Enum\Enum;

final class Fields extends Enum
{
  const All = 0;
  const DescriptionHtml = 1;
  const Metafields = 2;
  const Variants = 3;
  const Images = 4;
  const VariantMetafields = 5;
  const NewMetafields = 6;
  const Title = 7;
  const Tags = 8;


  public static function inEnum($array)
  {
    $keys = self::getKeys();
    $inEnum = true;
    foreach($array as $data)
    {
      if(!in_array($data->key, $keys))
      {
        $inEnum = false;
      }
    }
    return $inEnum;
  }


}

