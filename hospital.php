<?php
    require 'lib.php';
    // persiapkan curl
    $ch = curl_init(); 

    // set url 
    curl_setopt($ch, CURLOPT_URL, "https://corona.jatengprov.go.id/index.php/welcome/list_rs");

    // return the transfer as a string 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 

    // $output contains the output string 
    $output = curl_exec($ch); 

    // tutup curl 
    curl_close($ch);      

    $html = str_get_html($output);

    $bahan = $html->find('div[class=row justify-content-center mr-lg-5 mr-sm-5 ml-lg-5 ml-sm-5]');

    $array = array();
    
    foreach($bahan as $e){
        $i = 0;
        $j = 0;
        $name = $e->find('span[class=font-rs]');
        
        foreach($name as $n){
            $data = array();
            $data['name'] = $n->innertext;
            $array[$i] = $data;
            $i++;
        }

        $address = $e->find('li[class=list-group-item ml-6 mb-2 text-info-rs]');

        foreach($address as $s){
            $array[$j]['address'] = $s->innertext;
            $j++;
        }
        // $kotak = $bahan->find('div[class=col-lg-4 col-md-12 mb-2]');
        // echo $e->innertext . '<br>';
    }

    foreach($array as $row){
        $data_api = array(
            "id_province" => "856f1ab8-9c4e-4395-87dc-35949e30d2c9",
            "name" => $row['name'],
            "address" => $row['address']
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://localhost/covid19/hospital");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_api);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
    }

    echo "Sukses";
?>