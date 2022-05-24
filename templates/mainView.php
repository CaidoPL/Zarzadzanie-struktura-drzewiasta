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
    <div class="formsContainer">
        <form action="/Zadanie%20rekru/?action=create" method="post">
            <p>Dodaj nową gałąź</p>
            <label><input type="text" name="title" placeholder="Wprowadź nazwę"></label>
            <label>Wybierz rodzica: <select name="parent" id="">
                    <?php
                    foreach ($optionTree as $tree) {
                        echo "<option value='".$tree['id']."'>" . $tree['title'] . "</option>";
                    }
                    ?>
                </select></label>
                <input type="submit" value="add">
        </form>
    </div>
    <ul>
        <?php
        // dump($buildedTree);
        function loop($tree)
        {
            if (array_key_exists('children', $tree)) {
                foreach ($tree['children'] as $tree) {
                    echo "<li><ul>";
                    echo "<div class='list'>" . $tree['title'] . "</div>";
                    loop($tree);
                    echo "</li></ul>";
                }
            }
        }
        foreach ($buildedTree as $tree) {
            echo "<li><div class='list'>" . $tree['title'] . "</div></li>";
            loop($tree);
        }

        ?>
    </ul>
</body>

</html>