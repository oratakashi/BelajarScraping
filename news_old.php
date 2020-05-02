<?php
    require 'lib.php';

    // persiapkan curl
    $ch = curl_init(); 

    // set url 
    curl_setopt($ch, CURLOPT_URL, "https://www.detik.com/tag/virus-corona/?sortby=time&page=1");

    // return the transfer as a string 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 

    // $output contains the output string 
    $output = curl_exec($ch); 

    // tutup curl 
    curl_close($ch);      

    $html = str_get_html($output);

    $bahan = $html->find('div[class=list list--feed media_rows middle mb15 pb15 an_4_3]');

    $array = array();

    foreach ($bahan as $index => $e) {

        $title = $e->find('h2[class=title]');

        foreach ($title as $value) {
            $array[$index]['title'] = $value->innertext;
        }

        

        $link = $e->find('a');

        foreach ($link as $value) {
            $array[$index]['link'] = $value->href;
        }
    }

    echo json_encode($array);
?>