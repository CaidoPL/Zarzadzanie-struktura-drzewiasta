<?php

declare(strict_types=1);

namespace App\Controllers;

use App\View;
use App\Model\TreeModel;
use App\Request;

abstract class AbstractController
{
    protected const DEFAULT_ACTION = 'list';
    protected View $view;
    protected Request $request;
    protected TreeModel $treeModel;

    public function __construct(Request $request, array $config)
    {
        $this->treeModel = new TreeModel($config['db']);
        $this->View = new View();
        $this->request = $request;
    }

    public function run(): void
    {
        $action = $this->action() . 'Action';
        if (!method_exists($this, $action)) {
            $action = self::DEFAULT_ACTION . 'Action';
        }
        $this->$action();
    }

    private function action(): string
    {
        return $this->request->getParam('action', self::DEFAULT_ACTION);
    }
}
