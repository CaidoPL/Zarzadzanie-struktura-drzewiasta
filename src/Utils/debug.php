<?php

declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

function dump($data)
{
    echo '<div style=
        "
            display: inline-block;
            background-color: pink;
            border: 1px solid red;
            padding: 10px;
            margin: 5px;
        "
    >
    <pre>';
    print_r($data);
    echo '</div></pre></br>';
}
