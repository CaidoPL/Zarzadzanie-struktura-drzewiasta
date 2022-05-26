<?php

declare(strict_types=1);

namespace App\Model;

use PDO;
use Throwable;
use App\Exception\StorageException;

class TreeModel extends AbstractModel
{

    public function listTree(string $sortBy = 'ASC'): array
    {

        try {
            $q = "SELECT 
         parent_id,
         node.title AS title, node.id
         FROM tree AS node
         ORDER BY id $sortBy";
            $result = $this->conn->query($q);
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            throw new StorageException('Błąd przy pobieraniu drzewa z bazy danych');
        }
    }

    public function createBranch(array $params)
    {
        try {
            $title = $this->conn->quote($params['title']);
            $id = (int) $params['parent'];
            if ($id == 0) {
                $q4 = "INSERT INTO `tree` (`title`, `parent_id`) 
            VALUES ($title, 0)";
                $this->conn->query($q4);
            } else {
                $parent = $this->singleLeaf($id);
                $parentId = $parent[0]['id'];
                $q4 = "INSERT INTO `tree` (`title`,`parent_id`) 
        VALUES ($title, $parentId)";
                $this->conn->query($q4);
            }
        } catch (Throwable $e) {
            throw new StorageException('Błąd przy dodawaniu obiektu do bazy danych', 400, $e);
        }
    }

    public function deleteNode(int $id): void
    {
        try {
            $tree = $this->listTree();
            $buildedTree = $this->buildTree($tree, $id);
            $toDeleteId = [];
            foreach ($buildedTree as $childrenTree) {
                array_push($toDeleteId, $childrenTree['id']);
                if (array_key_exists('children', $childrenTree)) {
                    foreach ($childrenTree['children'] as $tree) {
                        array_push($toDeleteId, $tree['id']);
                    }
                }
            }
            $flag = true;
            array_push($toDeleteId, $id);
            $r = '';
            foreach ($toDeleteId as $value) {
                if ($flag == 'true') {
                    $r = $r . $value;
                    $flag = false;
                } else {
                    $r = $r . ',' . $value;
                };
            }
            $q1 = "DELETE FROM tree WHERE id IN(" . $r . ")";
            $this->conn->query($q1);
        } catch (Throwable $e) {
            throw new StorageException('Błąd przy usuwaniu obiektu z bazy danych');
        }
    }

    public function deleteLeaf(int $id): void
    {
        try {
            $toDelete = $this->singleLeaf($id);
            $oldParentId = $toDelete['0']['parent_id'];
            $q1 = "UPDATE tree SET parent_id = $oldParentId WHERE parent_id = $id";
            $this->conn->query($q1);
            $q2 = "DELETE FROM tree WHERE id = $id";
            $this->conn->query($q2);
        } catch (Throwable $e) {
            throw new StorageException('Błąd przy usuwaniu obiektu z bazy danych');
        }
    }

    public function moveNode(int $toMoveId, int $whereMoveId): string
    {
        try {
            $tree = $this->listTree();
            $buildedTree = $this->buildTree($tree, $toMoveId);
            $childrensId = [];
            foreach ($buildedTree as $childrenTree) {
                array_push($childrensId, $childrenTree['id']);
                if (array_key_exists('children', $childrenTree)) {
                    foreach ($childrenTree['children'] as $tree) {
                        array_push($childrensId, $tree['id']);
                    }
                }
            }
            if ($toMoveId == $whereMoveId) {
                return 'movedNodeError';
            } elseif (in_array($whereMoveId, $childrensId)) {
                return 'movedNodeError';
            } else {
                $q1 = "UPDATE tree SET parent_id = $whereMoveId WHERE id = $toMoveId";
                $this->conn->query($q1);
                return 'movedNode';
            }
        } catch (Throwable $e) {
            throw new StorageException('Błąd przy przenoszeniu obiektu');
        }
    }

    public function moveLeaf(int $toMoveId, int $whereMoveId): string
    {
        try {
            $toMove = $this->singleLeaf($toMoveId);
            $toMoveParent = $toMove['0']['parent_id'];
            if ($toMoveId == $whereMoveId) {
                return 'movedNodeError';
            } else {
                $q1 = "UPDATE tree SET parent_id = $toMoveParent WHERE parent_id = $toMoveId";
                $q2 = "UPDATE tree SET parent_id = $whereMoveId WHERE id = $toMoveId";
                $this->conn->query($q1);
                $this->conn->query($q2);
                return 'movedNode';
            }
        } catch (Throwable $e) {
            throw new StorageException('Błąd przy przenoszeniu obiektu');
        }
    }

    public function edit(array $params): void
    {
        try {
            $title = $this->conn->quote($params['title']);
            $id = (int) $params['id'];
            $q = "UPDATE tree SET title = '$title' WHERE id = $id";
            $this->conn->query($q);
        } catch (Throwable $e) {
            throw new StorageException('Błąd przy edycji obiektu');
        }
    }

    public function singleLeaf(int $id): array
    {
        $q = "SELECT parent_id,
        node.title AS title,
        node.id
        FROM tree AS node WHERE id = $id";
        $result = $this->conn->query($q);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buildTree(array $elements, int $parentId = 0): array
    {
        $branch = array();

        foreach ($elements as &$element) {
            if ($element['parent_id'] == $parentId) {
                $children = $this->buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[$element['id']] = $element;
                unset($element);
            }
        }
        return $branch;
    }
}
