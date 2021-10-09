<?php

namespace Erenkucukersoftware\BrugsMigrationTool\Exports;


use Maatwebsite\Excel\Concerns\{FromArray, WithHeadings, WithMapping};

class ProductsExport implements FromArray, WithHeadings,WithMapping
{
  public $data;
  public $operation;
  public $field;
  public function __construct(array $data,$operation ,$field)
  {
    $this->data = $data;
    $this->operation = $operation;
    $this->field = $field;
  }
  public function array(): array
  {
  return $this->data;
  }

  public function headings(): array
  {
    return [
      'Real Design',
      'Operation',
      'Field'
    ];
  }

  public function map($data): array
  {
    return [
      [
        $data['real_design'],
        $this->operation,
        $this->field
      ]
    ];
  }

}