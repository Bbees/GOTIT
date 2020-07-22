<?php

namespace App\Form;

use MyCLabs\Enum\Enum;

class Action extends Enum
{
  private const show = "show";
  private const create = "new";
  private const edit = "edit";
  private const delete = "delete";
}
