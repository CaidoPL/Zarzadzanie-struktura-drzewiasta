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

    public function optionTree(): array
    {
        $q = "SELECT 
        concat( repeat('~', COUNT(parent.id) - 1),child.title) 
        AS title,
        child.id
        FROM tree AS child,
        tree AS parent
        WHERE child.lft BETWEEN parent.lft AND parent.rgt
        GROUP BY child.id
        ORDER BY child.lft";
        $result = $this->conn->query($q);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createBranch(array $params)
    {
        $title = $params['title'];
        $id = (int) $params['parent'];
        if ($id == 0) {
            $q4 = "INSERT INTO `tree` (`title`,`lft`,`rgt`, `parent_id`) 
            VALUES ('$title',1, 2, 0)";
            $this->conn->query($q4);
        } else {
            $parent = $this->singleLeaf($id);
            $parentId = $parent[0]['id'];
            $childLft = $parent[0]['rgt'];
            $childRgt = $childLft + 1;
            $q2 = "UPDATE `tree` SET `lft` = `lft` + 2 WHERE `lft` >= $childLft";
            $q3 = "UPDATE `tree` SET `rgt` = `rgt` + 2 WHERE `rgt` >= $childRgt-1";
            $q4 = "INSERT INTO `tree` (`title`,`lft`,`rgt`, `parent_id`) 
        VALUES ('$title',$childLft, $childRgt, $parentId)";
            $this->conn->query($q2);
            $this->conn->query($q3);
            $this->conn->query($q4);
        }
    }

    public function singleLeaf(int $id): array
    {
        $q = "SELECT parent_id,
        node.title AS title,
        (SELECT count(parent.id)-1
             FROM tree AS parent
             WHERE node.lft BETWEEN parent.lft AND parent.rgt) AS depth,
        lft, rgt,
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

        $toDelete = $this->singleLeaf($id);
        $lft = $toDelete[0]['lft'];
        $rgt = $toDelete[0]['rgt'];
        $numToDelete = $rgt + 1 - $lft;
        $q1 = "DELETE FROM tree WHERE lft BETWEEN $lft AND $rgt";
        $q2 = "UPDATE `tree` SET `lft` = `lft` - $numToDelete WHERE `lft` >= $lft";
        $q3 = "UPDATE `tree` SET `rgt` = `rgt` - $numToDelete WHERE `rgt` >= $rgt";
        $this->conn->query($q1);
        $this->conn->query($q2);
        $this->conn->query($q3);
    }

    public function deleteLeaf(int $id): void
    {
        $toDelete = $this->singleLeaf($id);
        $lft = $toDelete[0]['lft'];
        $parentId = $toDelete[0]['parent_id'];
        $q1 = "UPDATE `tree` SET `lft` = `lft` - 1 WHERE `lft` > $lft";
        $q2 = "UPDATE `tree` SET `rgt` = `rgt` - 1 WHERE `rgt` > $lft";
        $q3 = "UPDATE `tree` SET `parent_id` = $parentId WHERE `parent_id` = $id";
        $q4 = "DELETE FROM tree WHERE `id` = $id";
        $this->conn->query($q1);
        $this->conn->query($q2);
        $this->conn->query($q3);
        $this->conn->query($q4);
    }

    public function moveNode(int $toMoveId, int $whereMoveId): string
    {
        $moveObject = $this->singleLeaf($toMoveId);
        $newParent = $this->singleLeaf($whereMoveId);
        $moveObjectRgt = $moveObject['0']['rgt'];
        $moveObjectLft = $moveObject['0']['lft'];
        $newParentRgt = $newParent['0']['rgt'];
        if ($moveObjectRgt < $newParentRgt) {
            $q1 = "UPDATE tree SET lft = CASE
            WHEN lft BETWEEN $moveObjectRgt + 1 AND $newParentRgt - 2 THEN lft - ($moveObjectRgt +1 - $moveObjectLft)
            WHEN lft BETWEEN $moveObjectLft AND $moveObjectRgt THEN lft + ($newParentRgt - 1 - $moveObjectRgt)
            END,
            rgt = CASE
            WHEN rgt BETWEEN $moveObjectRgt + 1 AND $newParentRgt - 1 THEN rgt - ($moveObjectRgt +1 - $moveObjectLft)
            WHEN rgt BETWEEN $moveObjectLft AND $moveObjectRgt THEN rgt + ($newParentRgt - 1 - $moveObjectRgt)
            END
            WHERE lft BETWEEN $moveObjectLft AND $newParentRgt - 1";
            $q2 = "Update tree SET rgt = $newParentRgt WHERE id = $whereMoveId";
            $q3 = "UPDATE tree SET parent_id = $whereMoveId WHERE id = $toMoveId";

            $this->conn->query($q1);
            $this->conn->query($q2);
            $this->conn->query($q3);
            return 'movedNode';
        }else{
            return 'movedNodeError';
        }
    }
}
