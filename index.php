<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Project/PHP/PHPProject.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>DWES T9 GIT, APIs y JMeter</title>
        <link rel="stylesheet" href="style.css">
        <link href="https://fonts.googleapis.com/css2?family=Chewy&display=swap" rel="stylesheet">
    </head>
    <body>
        <?php
        /**
         * @author Yenifer Quintero Moreno
         * @global string $url_datos define el endpoint del curl o el file_get_contents()
         * @global string $texto Datos del endpoint
         * @global json $datosJson datos pasados de $texto a formato json
         * @global string $idGato identificador de gato que se saca del json
         * @global array $tagsDescripcion se guardan las tags del json en un array
         */
        $url_datos = "https://cataas.com/cat?json=true";
        $texto = file_get_contents($url_datos);
        $datosJson = json_decode($texto, true);
        $idGato = null;
        $tagsDescripcion = [];
        /**
         * Comprobamos que en el json tenemos un id y le damos el valor a $idGato
         */
        if (isset($datosJson['id'])) {
            $idGato = $datosJson['id'];
        }
        /**
         * Comprobamos que en el json tenemos tags y le damos el valor al array $tagsDescripcion
         */
        if (isset($datosJson['tags']) && is_array($datosJson['tags'])) {
            $tagsDescripcion = $datosJson['tags'];
        }
        /**
         * Tenemos el endpoint de la imagen en base al $idGato
         */
        if ($idGato != null) {
            $curlImagen = "https://cataas.com/cat/$idGato";
        } else {
            $curlImagen = "https://cataas.com/cat";
        }
        /**
         * Realizamos el curl con curl_setopt para guardar el dato en string
         * y poder pasarlo a base64 para mostrarlo sin necesidad de guardar 
         * la imagen en nuestro servidor.
         */
        $curl = curl_init($curlImagen);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $imagen = curl_exec($curl);
        curl_close($curl);
        /**
         * Pasamos el string de imagen a base64 para poder mostrarlo
         */
        $imagenBase64 = base64_encode($imagen);
        ?>
        <h1>DWES Unidad 9: GIT, APIs y JMeter</h1>
        <br>
        <div id="container_gato">
        <form method="GET">
            <button type="submit">Buscar otro gato</button> 
        </form>
            <button id="btn_mostrarjson">Mostrar json</button> 
        <h2> Id del gato: <?php
            /**
             * Comprobamos si tenemos id para sacar un mensaje
             *  en caso de no tenerlo
             */
            if ($idGato != null) {
                echo " " . $idGato;
            } else {
                echo " No se pudo obtener el ID.";
            }
            ?> </h2>
        <h3> Descripción del gato: <?php
            /**
             * Comprobamos si tenemos datos en el array para sacar un mensaje
             *  en caso de no tener datos, además los separados por comas
             */
            if (!empty($tagsDescripcion)) {
                echo implode(", ", $tagsDescripcion);
            } else {
                echo " No se pudo obtener la descripción.";
            }
            ?> </h3>
        <h3>Foto del gato:</h3> 
        <img src="data:image/jpeg;base64,<?php echo $imagenBase64; ?>" alt="Gato de API" style="max-width:300px;">
        </div>
        <div id="container_json">
        <?php
        echo "<pre>";
        var_dump($datosJson);
        echo "</pre>";
        ?>
        </div>
        <script>
            const btn = document.getElementById("btn_mostrarjson");
            const container_json = document.getElementById("container_json");

            btn.addEventListener("click", function () {
                if (container_json.style.display === "none") {
                    container_json.style.display = "block";
                    btn.textContent = "Ocultar json";
                } else {
                    container_json.style.display = "none";
                    btn.textContent = "Mostrar json";
                }
            });
        </script>
    </body>
</html>
