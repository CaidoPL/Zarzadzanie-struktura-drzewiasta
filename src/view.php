<?php

declare(strict_types=1);

namespace App;

class View
{
    public function render(array $buildedTree, ?array $params): void
    {
        require_once("templates/layout.php");
    }
}
