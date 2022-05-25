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
        $newTree = $this->Model->buildTree($tree, 0);
        $params = [
            'alert' => $this->request->getParam('action', 'list'),
            'toDelete' => $this->request->getParam('toDelete', null),
            'move' => [
                'toMove' => $this->request->getParam('toMove', null),
                'whereMove' => $this->request->getParam('whereMove', null)
            ]
        ];
        $this->View->render($newTree, $params);
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

    public function deleteNodeAction(): void
    {
        if (!empty($this->request->getParam('id'))) {
            $deleteId = (int) $this->request->getParam('id');
            $this->Model->deleteNode($deleteId);
            header("Location: /Zadanie%20rekru/?action=deletedNode");
        } else {
            header("Location: /Zadanie%20rekru/");
        }
    }

    public function deleteLeafAction(): void
    {
        if (!empty($this->request->getParam('id'))) {
            $deleteId = (int) $this->request->getParam('id');
            $this->Model->deleteLeaf($deleteId);
            header("Location: /Zadanie%20rekru/?action=deletedLeaf");
        } else {
            header("Location: /Zadanie%20rekru/");
        }
    }

    public function moveNodeAction(){
        if (!empty($this->request->getParam('toMoveId')) && !empty($this->request->getParam('whereMoveId'))) {
            
            $toMoveId = (int) $this->request->getParam('toMoveId');
            $whereMoveId = (int) $this->request->getParam('whereMoveId');
            $alert = $this->Model->moveNode($toMoveId, $whereMoveId);
            header("Location: /Zadanie%20rekru/?action=$alert");
            
        } else {
            header("Location: /Zadanie%20rekru/");
        }
    }

    private function action(): string
    {
        return $this->request->getParam('action', self::DEFAULT_ACTION);
    }
}
