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
            case 'deletedNode':
                echo "<h3 style='color: red;'>Usunięto gałąź</h3>";
                break;
            case 'deletedLeaf':
                echo "<h3 style='color: red;'>Usunięto liść</h3>";
                break;
                echo "</div>";
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

    <div class="formsContainer">
        <form action="/Zadanie%20rekru/?action=create" method="post">
            <p>Dodaj nową gałąź</p>
            <label>Wprowadź nazwę: <input type="text" name="title"></label><br>
            <label>Wybierz rodzica: <select name="parent" id="">
                    <option value='0'>Główna kategoria</option>
                    <?php
                    foreach ($optionTree as $tree) {
                        echo "<option value='" . $tree['id'] . "'>" . $tree['title'] . "</option>";
                    }
                    ?>
                </select></label>
            <input type="submit" value="Dodaj">
        </form>
        <form action="/Zadanie%20rekru/" method="get">
            <p>Usuń gałąź</p>
            <label>Wybierz gałąź: <select name="toDelete" id="">
                    <?php
                    foreach ($optionTree as $tree) {
                        echo "<option value='" . $tree['id'] . "'>" . $tree['title'] . "</option>";
                    }
                    ?>
                </select></label>
            <input type="submit" value="Usuń">

        </form>

    </div>


    <div class="listCon">
        <ul>
            <?php
            // dump($params);
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