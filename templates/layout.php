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
    <?php
    require_once("templates/alert.php");
    ?>
    <?php if (!is_null($params['toDelete'])) {
        echo "<div class='hiddenCon'> <div class='hidden'>";
        echo "<a href='/?action=list'>Anuluj</a>";
        echo "<a href='/?action=deleteLeaf&id=" . $params['toDelete'] . "'>Usuń pojedyńczy obiekt</a>";
        echo "<a href='/?action=deleteNode&id=" . $params['toDelete'] . "'>Usuń cały węzeł</a>";
        echo "</div></div>";
    }
    ?>
    <?php if (!is_null($params['move']['toMove']) && !is_null($params['move']['whereMove'])) {
        echo "<div class='hiddenCon'> <div class='hidden'>";
        echo "<a href='/?action=list'>Anuluj</a>";
        echo "<a href='/?action=moveLeaf&toMoveId=" . $params['move']['toMove'] . "&whereMoveId=" . $params['move']['whereMove'] . "'>Przenieś pojedyńczy obiekt</a>";
        echo "<a href='/?action=moveNode&toMoveId=" . $params['move']['toMove'] . "&whereMoveId=" . $params['move']['whereMove'] . "'>Przenieś cały węzeł</a>";

        echo "</div></div>";
    }
    ?>

    <?php
    require_once("templates/forms.php");
    ?>

    <div class="listCon">
        <div class="linkCon">
            <p>Sortowanie wg. dodania do bazy</p>
            <a href='/?action=list&sortBy=ASC'>Od najstarszych</a>
            <a href='/?action=list&sortBy=DESC'>Od najnowszych</a>
        </div>
        <ul>
            <?php
            function loop($tree)
            {
                if (array_key_exists('children', $tree)) {
                    echo "<li><span class='caret caret-down'><div class='list'>" . $tree['title'] . "</div></span>";
                    echo "<ul class='nested active'>";
                    foreach ($tree['children'] as $tree) {
                        echo "<li>";
                        loop($tree);
                    }
                    echo "</ul>";
                } else {
                    echo "<li><div class='list'>" . $tree['title'] . "</div></li>";
                }
            }
            foreach ($buildedTree as $tree) {
                loop($tree);
            }
            ?>
        </ul>
    </div>

    <script type="text/javascript" src="Public/script.js"></script>

</body>

</html>