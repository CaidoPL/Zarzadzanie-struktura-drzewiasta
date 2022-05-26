<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Exception\StorageException;
use Throwable;

class TreeController extends AbstractController
{
    public function listAction(array $alert = []): void
    {
            $sortBy = $this->request->getParam('sortBy') ?? 'ASC';
            $tree = $this->treeModel->listTree($sortBy);
            $newTree = $this->treeModel->buildTree($tree, 0);
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
                    $this->treeModel->createBranch([
                        'title' => $this->request->postParam('title'),
                        'parent' => $parent
                    ]);
                    header("Location: /?action=created");
                } else {
                    header("Location: /");
                }
            } else {
                throw new StorageException("Błąd formularza: brak danych");
            }
    }

    public function deleteNodeAction(): void
    {
            if (!empty($this->request->getParam('id'))) {
                $deleteId = (int) $this->request->getParam('id');
                $this->treeModel->deleteNode($deleteId);
                header("Location: /?action=deletedNode");
            } else {
                header("Location: ");
            }
    }

    public function deleteLeafAction(): void
    {
            if (!empty($this->request->getParam('id'))) {
                $deleteId = (int) $this->request->getParam('id');
                $this->treeModel->deleteLeaf($deleteId);
                header("Location: /?action=deletedLeaf");
            } else {
                header("Location: /");
            }
    }

    public function moveNodeAction(): void
    {
            if (!empty($this->request->getParam('toMoveId')) && !empty($this->request->getParam('whereMoveId'))) {

                $toMoveId = (int) $this->request->getParam('toMoveId');
                $whereMoveId = (int) $this->request->getParam('whereMoveId');
                $alert = $this->treeModel->moveNode($toMoveId, $whereMoveId);
                header("Location: /?action=$alert");
            } else {
                header("Location: /");
            }
    }

    public function moveLeafaction(): void
    {
            if (!empty($this->request->getParam('toMoveId')) && !empty($this->request->getParam('whereMoveId'))) {
                $toMoveId = (int) $this->request->getParam('toMoveId');
                $whereMoveId = (int) $this->request->getParam('whereMoveId');
                $alert = $this->treeModel->moveLeaf($toMoveId, $whereMoveId);
                header("Location: /?action=$alert");
            } else {
                header("Location: /");
            }
    }

    public function editAction(): void
    {
            if ($this->request->hasPost()) {
                if (!empty($this->request->postParam('title')) && !empty($this->request->postParam('id'))) {
                    $this->treeModel->edit([
                        'title' => $this->request->postParam('title'),
                        'id' => $this->request->postParam('id')
                    ]);
                    header("Location: /?action=edited");
                } else {
                    header("Location: /");
                }
            } else {
                throw new StorageException("Błąd formularza: brak danych");
            }
    }
}
