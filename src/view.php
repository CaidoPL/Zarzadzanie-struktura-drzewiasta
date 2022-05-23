<?php

declare(strict_types=1);

namespace App;

class View
{
    public function render(array $list): void
    {
        require_once("templates/mainView.php");
        
    }
}
