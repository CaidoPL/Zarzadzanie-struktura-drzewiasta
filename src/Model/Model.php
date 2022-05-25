<?php

declare(strict_types=1);

namespace App\Model;

use PDO;
use PDOException;
use App\Exception\StorageException;
use App\Exception\ConfigurationException;

class Model
{
    protected PDO $conn;

    public function __construct(array $config)
    {
        try {
            $this->validateConfig($config);
            $this->createConnection($config);
        } catch (PDOException $e) {
            throw new StorageException('Connection error', 400, $e);
        }
    }

    private function validateConfig(array $config): void
    {
        if (
            empty($config['database'])
            || empty($config['host'])
            || empty($config['user'])
        ) {
            throw new ConfigurationException("Storage configuration Error");
        }
    }

    private function createConnection(array $config): void
    {
        $dsn = "mysql:dbname={$config['database']};host={$config['host']}";
        $this->conn = new PDO(
            $dsn,
            $config['user'],
            $config['password'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]
        );
    }

    public function listTree(): array
    {
        $q = "SELECT 
         parent_id,
         node.title AS title,
         (SELECT count(parent.id)-1
              FROM tree AS parent
              WHERE node.lft BETWEEN parent.lft AND parent.rgt) AS depth,
        lft, rgt,
         node.id
         FROM tree AS node
         ORDER BY node.lft";
        $result = $this->conn->query($q);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createBranch(array $params)
    {
        $title = $params['title'];
        $id = (int) $params['parent'];
        if ($id == 0) {
            $q4 = "INSERT INTO `tree` (`title`, `parent_id`) 
            VALUES ('$title', 0)";
            $this->conn->query($q4);
        } else {
            $parent = $this->singleLeaf($id);
            $parentId = $parent[0]['id'];
            $q4 = "INSERT INTO `tree` (`title`,`parent_id`) 
        VALUES ('$title', $parentId)";
            $this->conn->query($q4);
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


    public function deleteNode(int $id): void
    {   
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
        foreach($toDeleteId as $value){
            if ($flag == 'true') {
                $r = $r.$value;
                $flag = false;
            }else{
                $r = $r.','.$value;
            }; 
        }
        $q1 = "DELETE FROM tree WHERE id IN(".$r.")";
        $this->conn->query($q1);
    }

    public function deleteLeaf(int $id): void
    {
        $toDelete = $this->singleLeaf($id);
        $oldParentId = $toDelete['0']['parent_id'];
        $q1 = "UPDATE tree SET parent_id = $oldParentId WHERE parent_id = $id";
        $this->conn->query($q1);
        $q2 = "DELETE FROM tree WHERE id = $id";
        $this->conn->query($q2);
      
    }

    public function moveNode(int $toMoveId, int $whereMoveId): string
    {   
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
        if($toMoveId == $whereMoveId){
            return 'movedNodeError';
        }elseif(in_array($whereMoveId, $childrensId)){
            return 'movedNodeError';
        }else{
            $q1 = "UPDATE tree SET parent_id = $whereMoveId WHERE id = $toMoveId";
            $this->conn->query($q1);
            return 'movedNode';
        }   
    }
}
