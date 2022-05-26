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
    <form action="/?action=create" method="post">
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
    <form action="/" method="get">
        <p>Usuń obiekt</p>
        <label>Wybierz obiekt do usunięcia: <br><select name="toDelete" id="">
                <?php
                foreach ($buildedTree as $tree) {
                    echo "<option value='" . $tree['id'] . "'>" . $tree['title'] . "</option>";
                    loopOption($tree, '~');
                }
                ?>
            </select></label>
        <input type="submit" value="Usuń">

    </form>
    <form action="/" method="get">
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
            </select></label><br>
        <input type="submit" value="Przenieś">
    </form>
    <form action="/?action=edit" method="post">
        <p>Edytuj obiekt</p>
        <label>Wprowadź nową nazwę: <input type="text" name="title"></label><br>
        <label>Wybierz obiekt do edycji: <select name="id" id="">
                <?php
                foreach ($buildedTree as $tree) {
                    echo "<option value='" . $tree['id'] . "'>" . $tree['title'] . "</option>";
                    loopOption($tree, '~');
                }
                ?>
            </select></label>
        <input type="submit" value="Zmień">
    </form>

</div>