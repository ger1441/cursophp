<?php
    echo "EJERCICIO 1<br>";
    $arreglo1 = [
        'keyStr1' => 'lado',
        0 => 'ledo',

        'keyStr2' => 'lido',
        1 => 'lodo',
        2 => 'ludo',
    ];

    foreach ($arreglo1 as $element1){
        echo $element1.",";
    }
    echo "<br>decirlo al revés lo dudo.<br>";
    $arreglo1_aux = array_reverse($arreglo1);
    foreach ($arreglo1_aux as $element1_aux){
        echo $element1_aux.",";
    }
    echo "<br>¡Qué trabajo me ha costado!";

    echo "<hr>";
    echo "<br>EJERCICIO 2<br>";
    $arreglo2 = [
        "México" => ["Puebla","Tlaxcala","Cd Mexico"],
        "Colombia" => ["Bogota","Cali","Medellin"],
        "Francia" => ["Paris","Lyon","Niza"],
        "España" => ["Barcelona","Madrid","Sevilla"],
    ];
    foreach ($arreglo2 as $keyA2 => $element2){
        $ciudades = implode(" ",$element2);
        echo $keyA2.": ".$ciudades;
        echo "<br>";
    }

    echo "<hr>";
    echo "<br>EJERCICIO 3<br>";
    $valores = [23, 54, 32, 67, 34, 78, 98, 56, 21, 34, 57, 92, 12, 5, 61];
    rsort($valores);
    echo "Números más grandes: <br>";
    for($i=0;$i<3;$i++) echo $valores[$i]." ";
    echo "<br>Números más bajos: <br>";
    for($i=(count($valores)-1);$i>=(count($valores)-3);$i--) echo $valores[$i]." ";
