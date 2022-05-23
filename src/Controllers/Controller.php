<?php

declare(strict_types=1);

namespace App\Controllers;
use App\View;
use App\Model\Model;
use App\Exception\NotFoundException;
use App\Request;

class Controller
{
    protected const DEFAULT_ACTION = 'list';
    protected View $view;
    protected Request $request;
    protected Model $Model;

    public function __construct(Request $request, array $config)
    {
        $this->Model = new Model($config['db']);
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

    public function listAction()
    {
        $tree = $this->Model->listTree();
        $newTree = $this->Model->buildTree($tree, 0);
        $this->View->render($newTree);
        
        
    }

    private function action(): string
    {
        return $this->request->getParam('action', self::DEFAULT_ACTION);
    }

}