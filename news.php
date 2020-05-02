<?php
    require 'lib.php';

    // persiapkan curl for Detik dot com
    $ch = curl_init(); 

    // set url for Detik Dot Com
    curl_setopt($ch, CURLOPT_URL, "https://www.detik.com/tag/virus-corona/?sortby=time&page=".$_GET['page']);

    // return the transfer as a string 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 

    // $output contains the output string 
    $output = curl_exec($ch); 

    // tutup curl 
    curl_close($ch);      

    $html  = str_get_html($output);

    $bahan = $html->find('div[class=list list--feed media_rows middle mb15 pb15 an_4_3]');

    $output_detik = array();

    foreach($bahan as $index => $value){
        $index_title = 0;
        $index_date = 0;
        $index_desc = 0;
        $index_image = 0;
        $index_link = 0;
        /**
         * Get Data Title
         */
        $title = $value->find('h2[class=title]');
        foreach($title as $row){
            $output_detik[$index_title]['title'] = $row->innertext;
            $index_title++;
        }

        /**
         * Get Data Date
         */
        $date = $value->find('span[class=date]');
        foreach($date as $row){
            $output_detik[$index_date]['date'] = $row->innertext;
            $index_date++;
        }

        /**
         * Get Data Description
         */
        $description = $value->find('p');
        foreach ($description as $row) {
            $output_detik[$index_desc]['description'] = $row->innertext;
            $index_desc++;
        }

        /**
         * Get Data Image
         */
        $image = $value->find('img');

        foreach($image as $row){
            $output_detik[$index_image]['image'] = $row->src;
            $index_image++;
        }

        /**
         * Get Article Link
         */
        $link = $value->find('a');

        foreach ($link as $row) {
            $output_detik[$index_link]['link'] = $row->href;
            $output_detik[$index_link]['source'] = "Detik.com";
            $index_link++;
        }
    }

    /**
     * Get Data For Kompas Dot Com
     * Source Url : https://www.kompas.com/covid-19?page=1
     */

    // persiapkan curl for Kompas Dot Com
    $ch = curl_init(); 

    // set url for Kompas Dot Com
    curl_setopt($ch, CURLOPT_URL, "https://www.kompas.com/covid-19?page=".$_GET['page']);

    // return the transfer as a string 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 

    // $output contains the output string 
    $output = curl_exec($ch); 

    // tutup curl 
    curl_close($ch);  

    $html = str_get_html($output); //Convert html to object

    /**
     * Find Data, Find div class from Source 
     */

    $output_kompas = array();

    $bahan = $html->find('div[class=latest ga--latest mt2 clearfix]');

    foreach ($bahan as $index => $value) {
        $index_title = 0;
        $index_date = 0;
        $index_image = 0;
        /**
         * Get Article Title
         */

        $title = $value->find('a[class=article__link]');

        foreach ($title as $row) {
            $output_kompas[$index_title]['title'] = $row->innertext;
            $output_kompas[$index_title]['link'] = $row->href;
            $output_kompas[$index_title]['source'] = "Kompas.com";
            $output_kompas[$index_title]['description'] = "null";

            $index_title++;
        }

        /**
         * Get Article Date
         */
        $date = $value->find('div[class=article__date]');
        
        foreach ($date as $row) {
            $output_kompas[$index_date]['date'] = $row->innertext;
            $index_date++;
        }

        /**
         * Get Article Images
         */
        $image = $value->find('img');

        foreach($image as $row){
            $output_kompas[$index_image]['image'] = $row->src;
            $index_image++;
        }
    }


    /**
     * Get Data For CNN Dot Com
     * Source Url : https://www.cnnindonesia.com/tag/covid_19
     */

    // persiapkan curl for Kompas Dot Com
    $ch = curl_init(); 

    // set url for Kompas Dot Com
    curl_setopt($ch, CURLOPT_URL, "https://www.cnnindonesia.com/tag/covid_19/".$_GET['page']);

    // return the transfer as a string 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 

    // $output contains the output string 
    $output = curl_exec($ch); 

    // tutup curl 
    curl_close($ch);  

    $output_cnn = array();

    /**
     * Parsing to Object
     */
    $html = str_get_html($output);

    $bahan = $html->find('div[class=list media_rows middle]');

    foreach ($bahan as $index => $value) {
        $index_title = 0;
        $index_date = 0;
        $index_image = 0;
        $index_link = 0;

        /**
         * Get CNN Title
         */
        $title = $value->find('h2[class=title]');

        foreach ($title as $row ) {
            $output_cnn[$index_title]['title'] = $row->innertext;
            $index_title++;
        }

        /**
         * Get CNN Date
         */
        $date = $value->find('span[class=date]');
        foreach ($date as $row ) {
            $val = $row->innertext;
            $val = explode("lalu", $val);
            $val = str_replace("<!--", "", $val[1]);
            $val = str_replace("-->", "", $val);

            $output_cnn[$index_date]['date'] = $val;

            $index_date++;
        }

        /**
         * Get CNN Image
         */
        $image = $value->find('img');

        foreach($image as $row){
            $output_cnn[$index_image]['image'] = $row->src;
            $index_image++;
        }

        /**
         * Get CNN Link
         */
        $link = $value->find('a');
        foreach($link as $row){
            $output_cnn[$index_link]['link'] = $row->href;
            $output_cnn[$index_link]['source'] = "CNN.com";

            $index_link++;
        }
    }

    /**
     * Output Json from one Source
     */
    // echo json_encode($output_cnn);

    /**
     * Output Json All Source
     */
    echo json_encode(array_merge($output_detik, $output_kompas, $output_cnn));
?>