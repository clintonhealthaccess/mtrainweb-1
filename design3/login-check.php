<?php
    if($_POST['username'] =='admin' && $_POST['password'] =='admin')
        {
        header("Location: dashboard.html");
        }
        else
            {
            header("Location: index.php?failed=true");
            }
?>
