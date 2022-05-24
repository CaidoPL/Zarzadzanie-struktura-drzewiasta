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

    public function listAction(array $alert = []): void
    {
        $tree = $this->Model->listTree();
        $optionTree = $this->Model->optionTree();
        $newTree = $this->Model->buildTree($tree, 0);
        $this->View->render($newTree, $optionTree, $this->request->getParam('action'));
    }

    public function createAction(): void
    {
        if ($this->request->hasPost()) {
            if (!empty($this->request->postParam('title'))) {
                if (empty($this->request->postParam('parent'))) {
                    $parent = 0;
                } else {
                    $parent = $this->request->postParam('parent');
                }
                $this->Model->createBranch([
                    'title' => $this->request->postParam('title'),
                    'parent' => $parent
                ]);
                header("Location: /Zadanie%20rekru/?action=created");
            } else {
                header("Location: /Zadanie%20rekru/");
            }
        }
    }

    public function deleteAction(): void
    {
        if ($this->request->hasPost()) {
            if (!empty($this->request->postParam('toDelete'))) {
                $deleteId = (int) $this->request->postParam('toDelete');
                $this->Model->deleteNode($deleteId);
                header("Location: /Zadanie%20rekru/?action=deleted");
            } else {
                header("Location: /Zadanie%20rekru/");
            }
        } else {
            header("Location: /Zadanie%20rekru/");
        }
    }

    private function action(): string
    {
        return $this->request->getParam('action', self::DEFAULT_ACTION);
    }
}
