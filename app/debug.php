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

<<<<<<< HEAD
function clear_db()
{
    include('connect.php');
    if (mysql_query("DELETE FROM users;") and
        mysql_query("DELETE FROM waiting;") and
        mysql_query("DELETE FROM events;")) {
        echo "<h2>All tables cleared!</h2>";
    }
    mysql_close($dbhandle);
}



=======
>>>>>>> a66f0aae62f39f80f0a0ade52c190139e932290f
?>
