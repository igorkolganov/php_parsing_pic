<?php
include "func/includes/autoloader.php";
include_once "func/includes/simple_html_dom.php";
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Document</title>
</head>
<body>



<div class="data-task">

    <?php
    $doneJobe = new DoneJobe('https://www.rbc.ru/short_news', 14);
    $allData = $doneJobe->getSomeData($_GET['val']);

    foreach ($allData as $key => $value){
        echo '<div class="article" style="border: 1px solid black; border-radius: 5px; width: 500px; margin-left: auto; margin-right: auto;"><h3>' . $value['title'] . '</h3><br>';
        echo '<p><b>' . $value['dates'] . '</b></p>';
        echo '<p style="overflow-x: auto;">' . $value['body'] . '</p>';
        echo '<p>' . '<img src="' . $value['picture'] . '" alt=""></p><br>';
    }
    ?>

</div>

</body>
</html>