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
            throw new StorageException('Connection error',400,$e);
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

        // listing

        // $q= "SELECT title,lft FROM tree ORDER BY lft";
        // $result = $this->conn->query($q);
        // $note = $result->fetchAll(PDO::FETCH_ASSOC);
        // dump($note);

        // depth
        // $q = "SELECT child.title, count(parent.id)-1 AS depth
        // FROM tree AS child,
        // tree AS parent
        // WHERE child.lft BETWEEN parent.lft AND parent.rgt
        // GROUP BY child.id
        // ORDER BY child.lft";
    }

    public function listTree()
    {
         $q = "SELECT 
         parent_id,
         node.title AS title,
         (SELECT count(parent.id)-1
              FROM tree AS parent
              WHERE node.lft BETWEEN parent.lft AND parent.rgt) AS depth,
         node.id
         FROM tree AS node
         ORDER BY node.lft";
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