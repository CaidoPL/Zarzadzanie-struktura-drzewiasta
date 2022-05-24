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

    public function listAction(): void
    {
        $tree = $this->Model->listTree();
        $optionTree = $this->Model->optionTree();
        $newTree = $this->Model->buildTree($tree, 0);
        $this->View->render($newTree, $optionTree);
    }

    public function createAction()
    {
        if ($this->request->hasPost()) {
            if (!empty($this->request->postParam('title'))) {
                $this->Model->createBranch([
                    'title' => $this->request->postParam('title'),
                    'parent' => $this->request->postParam('parent')
                ]);
                $tree = $this->Model->listTree();
                $optionTree = $this->Model->optionTree();
                $newTree = $this->Model->buildTree($tree, 0);
                $this->View->render($newTree, $optionTree, ['alert' => 'add']);
            }else{
                header("Location: /Zadanie%20rekru/");
            }
        }
        
    }

    private function action(): string
    {
        return $this->request->getParam('action', self::DEFAULT_ACTION);
    }
}
