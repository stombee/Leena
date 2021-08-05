<?php

namespace Erenkucukersoftware\BrugsMigrationTool;


class Constants
{
  public static $ENTITY_TYPES = ['Products'];

  public static $UPDATEABLE_PRODUCT_FIELDS = [
    'tags', 'descriptionHtml', 'metafields'
  ];
  public static $SPECIAL_MATERIAL_CATEGORIES = [
    'bamboo', 'cotton', 'hemp', 'jute', 'leather','sisal', 'fiber', 'other', 'recycled', 'silk', 'synthetics', 'wool'
  ];
  public static $SPECIAL_STYLE_CATEGORIES = [
    'animal', 'aztec rugs', 'bohemian', 'coastal', 'contemporary', 'country & floral', 'farmhouse', 'geometric', 'kids & novelty', 'moroccan rugs', 'one of a kind', 'outdoor', 'persian rugs', 'shags', 'solid & striped', 'southwestern', 'traditional', 'transitional', 'vintage'
  ];
  public static $SPECIAL_COLOR_CATEGORIES = [
            'Grey' => 'Black & Grey Rugs',
            'Gray' => 'Black & Grey Rugs',
            'Neutral' => 'White Rugs',
            'Pink' => 'Pink Rugs',
            'Brown' => 'Brown Rugs',
            'Natural' => 'White Rugs',
            'Green' => 'Green Rugs',
            'Tan' => 'White Rugs',
            'Beige' => 'White Rugs',
            'Orange' => 'Orange Rugs',
            'Black' => 'Black & Grey Rugs',
            'Red' => array('Red Rugs', 'Burgundies', 'Pink Rugs'),
            'Blue' => 'Blue Rugs',
            'Yellow' => 'Yellow & Gold Rugs',
            'Off-White' => 'White Rugs',
            'Khaki' => 'Yellow & Gold Rugs',
            'White' => 'White Rugs',
            'Purple' => 'Purple Rugs',
            'Navy' => 'Blue Rugs',
            'Silver' => 'Black & Grey Rugs',
            'Multi-colored' => 'Multi Color Rugs',
            'Kahki' => 'Yellow & Gold Rugs',
            'Black & White' => 'Black & Grey Rugs',
            'Gold' => 'Yellow & Gold Rugs',
            'Copper' => array('Yellow & Gold Rugs', 'Brown Rugs'),
            'Light Yellow' => 'Yellow & Gold Rugs',
            'Denim' => 'Blue Rugs',
            'Bright Pink' => 'Pink Rugs',
            'Pale Blue' => 'Blue Rugs',
            'Teal' => array('Green Rugs', 'Blue Rugs'),
            'Light Brown' => 'Brown Rugs',
            'Dark Brown' => 'Brown Rugs',
            'Light Gray' => 'Black & Grey Rugs',
            'Bright Yellow' => 'Yellow & Gold Rugs',
            'Dark Blue' => 'Blue Rugs',
            'Dark Purple' => 'Purple Rugs',
            'Metallic' => 'Black & Grey Rugs'
  ];



}
