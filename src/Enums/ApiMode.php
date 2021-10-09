<?php

namespace Erenkucukersoftware\BrugsMigrationTool\Enums;

use BenSampo\Enum\Enum;

final class ApiMode extends Enum
{
  const GraphQL = 0;
  const Rest = 1;
}
