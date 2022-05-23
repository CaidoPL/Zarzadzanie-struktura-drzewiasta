<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        li{
            list-style: none;
        }
        .list{
            display: inline-block;
            background-color: cyan;
            padding: 2px;
            margin: 2px;
            border: 2px solid black;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <ul>
        <?php
        function loop($tree)
        {
            if (array_key_exists('children', $tree)) {
                foreach ($tree['children'] as $tree) {
                    echo "<li><ul>";
                    echo "<div class='list'>".$tree['title']."</div>";
                    loop($tree);
                    echo "</li></ul>";
                }
            }
        }
        foreach ($list as $tree) {
            echo "<li><div class='list'>".$tree['title']."</div></li>";
            loop($tree);
        }

        ?>
    </ul>
</body>

</html>