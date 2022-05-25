<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="Style/style.css">
</head>

<body>

    <?php
    if ($params['alert']) {
        echo "<div class='alert'>";
        switch ($params['alert']) {
            case 'created':
                echo "<h3 style='color: red;'>Dodano nową gałąź</h3>";
                break;
            case 'ErrorMissingTitle':
                echo "<h3 style='color: red;'>Wprowadź tytuł przed dodaniem</h3>";
                break;
            case 'deletedNode':
                echo "<h3 style='color: red;'>Usunięto gałąź</h3>";
                break;
            case 'deletedLeaf':
                echo "<h3 style='color: red;'>Usunięto liść</h3>";
                break;
            case 'movedNode':
                echo "<h3 style='color: red;'>Przeniesiono gałąź</h3>";
                break;
            case 'movedNodeError':
                echo "<h3 style='color: red;'>Nie można przesunąć gałęzi</h3>";
                break;
        }
    }
    ?>
    <?php if (!is_null($params['toDelete'])) {
        echo "<div class='hiddenCon'> <div class='hidden'>";
        echo "<a href='/Zadanie%20rekru/?action=list'>Anuluj</a>";
        echo "<a href='/Zadanie%20rekru/?action=deleteNode&id=" . $params['toDelete'] . "'>Usuń cały węzeł</a>";
        echo "<a href='/Zadanie%20rekru/?action=deleteLeaf&id=" . $params['toDelete'] . "'>Usuń pojedyńczy liść</a>";

        echo "</div></div>";
    }
    ?>
    <?php if (!is_null($params['move']['toMove']) && !is_null($params['move']['whereMove'])) {

        echo "<div class='hiddenCon'> <div class='hidden'>";
        echo "<a href='/Zadanie%20rekru/?action=list'>Anuluj</a>";
        echo "<a href='/Zadanie%20rekru/?action=moveLeaf&toMoveId=" . $params['move']['toMove'] . "&whereMoveId=" . $params['move']['whereMove'] . "'>Przenieś sam obiekt</a>";
        echo "<a href='/Zadanie%20rekru/?action=moveNode&toMoveId=" . $params['move']['toMove'] . "&whereMoveId=" . $params['move']['whereMove'] . "'>Przenieś cały węzeł</a>";

        echo "</div></div>";
    }
    ?>
    <?php
    function loopOption($tree, $pauza)
    {
        if (array_key_exists('children', $tree)) {
            foreach ($tree['children'] as $tree) {
                echo "<option value='" . $tree['id'] . "'>" . $pauza . $tree['title'] . "</option>";
                loopOption($tree, $pauza . '~');
            }
        }
    }
    ?>

    <div class="formsContainer">
        <form action="/Zadanie%20rekru/?action=create" method="post">
            <p>Dodaj nowy obiekt</p>
            <label>Wprowadź nazwę: <input type="text" name="title"></label><br>
            <label>Wybierz rodzica: <select name="parent" id="">
                    <option value='0'>Nowy korzeń</option>
                    <?php
                    foreach ($buildedTree as $tree) {
                        echo "<option value='" . $tree['id'] . "'>" . $tree['title'] . "</option>";
                        loopOption($tree, '~');
                    }
                    ?>
                </select></label>
            <input type="submit" value="Dodaj">
        </form>
        <form action="/Zadanie%20rekru/" method="get">
            <p>Usuń obiekt</p>
            <label>Wybierz obiekt do usunięcia: <select name="toDelete" id="">
                    <?php
                    foreach ($buildedTree as $tree) {
                        echo "<option value='" . $tree['id'] . "'>" . $tree['title'] . "</option>";
                        loopOption($tree, '~');
                    }
                    ?>
                </select></label>
            <input type="submit" value="Usuń">

        </form>
        <form action="/Zadanie%20rekru/" method="get">
            <p>Przenieś obiekt</p>
            <label>Wybierz obiekt do przeniesienia: <select name="toMove" id="">
                    <?php
                    foreach ($buildedTree as $tree) {
                        echo "<option value='" . $tree['id'] . "'>" . $tree['title'] . "</option>";
                        loopOption($tree, '~');
                    }
                    ?>
                </select></label><br>
            <label>Wybierz miejsce do którego obiekt zostanie przeniesiony <select name="whereMove" id="">
                    <?php
                    foreach ($buildedTree as $tree) {
                        echo "<option value='" . $tree['id'] . "'>" . $tree['title'] . "</option>";
                        loopOption($tree, '~');
                    }
                    ?>
                </select></label>
            <input type="submit" value="Przenieś">

        </form>

    </div>


    <div class="listCon">
        <ul>

            <?php
            dump($buildedTree);
            function loop($tree)
            {
                if (array_key_exists('children', $tree)) {
                    foreach ($tree['children'] as $tree) {
                        echo "<li><ul>";
                        echo "-<div class='list'>" . $tree['title'] . "</div>";
                        loop($tree);
                        echo "</li></ul>";
                    }
                }
            }
            foreach ($buildedTree as $tree) {
                echo "<li>-<div class='list'>" . $tree['title'] . "</div></li>";
                loop($tree);
            }

            ?>
        </ul>
    </div>
</body>

</html>