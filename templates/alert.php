<?php
if ($params['alert']) {
    echo "<div id='alert'>";
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
        case 'movedNode':
            echo "<h3 style='color: red;'>Przeniesiono gałąź</h3>";
            break;
        case 'edited':
            echo "<h3 style='color: red;'>Zmodyfikowano wybrany obiekt</h3>";
            break;
    }
    echo "</div>";
}

if ($params['alert']) {
    echo "<div id='alert'>";
    switch ($params['alert']) {
        case 'ErrorMissingTitle':
            echo "<h3 style='color: red;'>Wprowadź tytuł przed dodaniem</h3>";
            break;
        case 'movedNodeError':
            echo "<h3 style='color: red;'>Nie można przesunąć gałęzi</h3>";
            break;
    }
    echo "</div>";
}
