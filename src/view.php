<?php

declare(strict_types=1);

namespace App;

class View
{
    public function render(array $buildedTree, array $optionTree, array $alert = []): void
    {
        require_once("templates/mainView.php");
    }
}
