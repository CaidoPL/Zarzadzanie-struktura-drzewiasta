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
    if ($param) {
        echo "<div class='alert'>";
        switch ($param) {
            case 'created':
                echo "<h3 style='color: red;'>Dodano nową gałąź</h3>";
                break;
            case 'delete':
                echo "<h3 style='color: red;'>Usunięto gałąź</h3>";
                break;
                echo "</div>";
        }
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
        <form action="/Zadanie%20rekru/?action=delete" method="post">
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
        <?php if ($param == 'deleteBar') : ?>
            <div class="hiddenCon">
                <div class="hidden"></div>
            </div>
        <?php endif ?>
    </div>
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
</body>

</html>