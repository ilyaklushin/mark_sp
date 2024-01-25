<?php
function mark_sGET($url){
    return file_get_contents($url, false);
}

function mark_read_dir($dirname) {
    $rtrn=array();
    foreach (glob($dirname."/*.txt") as $filename) {
        echo $filename;
        echo "readfile: $filename";
        var_dump(file_get_contents($filename));
        foreach (explode(PHP_EOL,file_get_contents($filename)) as $row){
            if (base64_decode($row, true)!=false){
                $rtrn[]=$row;
            }
        }
    }
    return $rtrn;
}

function mark_decode_to_arr($src_arr, $progress){
    $rtrn=array();
    $pval=0;
    foreach ($src_arr as $row){
        $d_row=base64_decode($row);
        $gtin=substr($d_row, 0, 14);
        if (strlen($rtrn[$gtin]["name"])<3){
            $gtin_name=mark_sGET("http://srs.gs1ru.org/id/gtin/".substr($d_row, 1, 13));
            $gtin_name=substr($gtin_name, strpos($gtin_name,'product-card__header-product-name')+35);
            $gtin_name=trim(substr($gtin_name, 0, stripos($gtin_name, '</p>')));
            $gtin_name_raw=$gtin_name;
            $gtin_name=transliterator_transliterate('Russian-Latin/BGN; Any-Latin; Latin-ASCII; NFD; [:Nonspacing Mark:] Remove; NFC;',$gtin_name);
            $rtrn[$gtin]=array("name"=>$gtin_name, "rawname"=>$gtin_name_raw);
        }
        $rtrn[$gtin]["code"][]=$d_row;
        $pval++;
        $progress->setValue($pval);
//        var_dump($gtin_name,$d_row, substr ($d_row, 1, 13));

    }
    return $rtrn;
}

function mark_save_to_file($dst, $gtin, $name, $file_arr){
    $fp=fopen($dst, 'w');
    fwrite($fp, "$gtin\n$name\n".implode("\n", $file_arr));
    fclose($fp);
    //file_put_contents($dst, "$gtin\n$name\n".implode("\n", $file_arr));
}

function mark_save_to_file_clean($dst, $file_arr){
    $fp=fopen($dst, 'w');
    fwrite($fp, implode("\n", $file_arr));
    fclose($fp);
}

function mark_btn_run($progress, $srcdir, $dstdir, $by_gtin=true, $clean_out=false) {
    $a_src=mark_read_dir($srcdir);
    $progress->setRange(0, count($a_src));
    $d_arr=mark_decode_to_arr($a_src, $progress);
    if (!file_exists($dstdir)) {
        mkdir($dstdir, 0777, true);
    }

    if ($by_gtin && $clean_out){
        foreach ($d_arr as $gtin => $data){
            mark_save_to_file_clean($dstdir."/".$gtin.".txt", $data["code"]);
        }
    } elseif($by_gtin && !$clean_out){
        foreach ($d_arr as $gtin => $data){
            mark_save_to_file($dstdir."/".$gtin.".txt", $gtin, $data["rawname"], $data["code"]);
        }
    } elseif(!$by_gtin && $clean_out){
        foreach ($d_arr as $gtin => $data){
            mark_save_to_file_clean($dstdir."/".preg_replace('/[^A-Za-z0-9 ]/','',$data["name"]).".txt", $data["code"]);
        }
    } else{
        foreach ($d_arr as $gtin => $data){
            mark_save_to_file($dstdir."/".preg_replace('/[^A-Za-z0-9 ]/','',$data["name"]).".txt", $gtin, $data["rawname"], $data["code"]);
        }
    }

}
