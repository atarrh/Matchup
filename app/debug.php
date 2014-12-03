<?php

function print_funcs($obj)
{
    $funcs = get_class_methods($obj);
    $objtype = gettype($obj);

    echo "<p>Below are the functions for an object of type $objtype</p>";    
    echo "<ul>";
    foreach ($funcs as $method_name) {
        echo "<li>$method_name</li>";
    }
    echo "</ul>";
}

?>
