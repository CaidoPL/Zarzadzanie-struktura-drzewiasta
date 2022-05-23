<?php

declare(strict_types=1);

namespace App\Controllers;
use App\View;
use App\Model\Model;
class Controller
{
    private static array $config = [];
    protected View $view;
    protected Model $model;

    public function __construct(array $config)
    {
        $this->Model = new Model($config['db']);
        $this->View = new View();
        $this->list();
    }

    public function list()
    {
        $this->View->render($this->Model->listTree());
    }

}