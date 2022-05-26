<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="Public/style.css">
</head>

<body>
    <script type="text/javascript" src="Public/script.js"></script>
    <?php
    require_once("templates/alert.php");
    ?>
    <?php if (!is_null($params['toDelete'])) {
        echo "<div class='hiddenCon'> <div class='hidden'>";
        echo "<a href='/?action=list'>Anuluj</a>";
        echo "<a href='/?action=deleteNode&id=" . $params['toDelete'] . "'>Usuń cały węzeł</a>";
        echo "<a href='/?action=deleteLeaf&id=" . $params['toDelete'] . "'>Usuń pojedyńczy liść</a>";

        echo "</div></div>";
    }
    ?>
    <?php if (!is_null($params['move']['toMove']) && !is_null($params['move']['whereMove'])) {

        echo "<div class='hiddenCon'> <div class='hidden'>";
        echo "<a href='/?action=list'>Anuluj</a>";
        echo "<a href='/?action=moveLeaf&toMoveId=" . $params['move']['toMove'] . "&whereMoveId=" . $params['move']['whereMove'] . "'>Przenieś sam obiekt</a>";
        echo "<a href='/?action=moveNode&toMoveId=" . $params['move']['toMove'] . "&whereMoveId=" . $params['move']['whereMove'] . "'>Przenieś cały węzeł</a>";

        echo "</div></div>";
    }
    ?>
    
    <?php
        require_once("templates/forms.php");
    ?>
    <div class="listCon">
        <ul>
            <?php
            // dump($buildedTree);
            function loop($tree)
            {
                if (array_key_exists('children', $tree)) {
                    foreach ($tree['children'] as $tree) {

                        echo "<ul>";
                        echo $tree['title'];
                        loop($tree);
                        echo "</ul>";
                    }
                }
            }

            foreach ($buildedTree as $tree) {
                echo "<li>".$tree['title']."</li>";
                loop($tree);
            }

            ?>
        </ul>
    </div>
</body>

</html>