<?php

declare(strict_types=1);

namespace App;

use App\Request;

class View
{
    public function render(array $buildedTree, array $optionTree, ?string $param): void
    {
        require_once("templates/mainView.php");
    }
}
