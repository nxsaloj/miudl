<?php

namespace App\Utils;

class Utils{
    public static function getSortBy($campo, $type, $instance,$extras=[], $default="Nombre",$ord="ASC"){
        $permitidos = $instance->getFillable();
        $global = ["Created_at","Updated_at","Deleted_at"];
        $tabla = $instance->getTable();
        $map = (method_exists($instance,'getMap'))? $instance->getMap():null;
        $relational = false;

        $orderby = null;
        if($campo && $type)
        {
            if(in_array($campo, $permitidos) || in_array($campo, $extras) || in_array($campo, $global))
            {
                $set = false;
                if($map)
                {
                    if(isset($map[$campo]))
                    {
                        $campo = $map[$campo]['table'].'.'.$map[$campo]['field'];
                        $relational = true;
                        $set = true;
                    }
                }
                $orderby['field'] = ($set !== true)? $tabla.".".$campo:$campo;
            }
            if($orderby != null)
            {
                $orderby['type'] = ($type == 'true')? 'ASC':'DESC';
                $orderby['relational'] = $relational;
                return $orderby;
            }
        }
        return array("field"=>(strpos($default, '.') !== false)? $default:$tabla.'.'.$default,"type"=>$ord,"relational"=>$relational);
    }

    public static function getFieldsJoin($instance,$extra=[],$only=null, $ignore=null)
    {
        $primary = $instance->getKeyName();
        $fields = [];
        $tabla = $instance->getTable();
        $campos = $instance->getFillable();

        if($primary){
            array_push($fields, $tabla.".".$primary);
        }
        foreach($campos as $campo)
        {
            if(!$only) array_push($fields, $tabla.'.'.$campo);
            else
            {
                if(in_array($campo, $only))
                {
                    if(!$ignore) array_push($fields, $tabla.'.'.$campo);
                    else
                    {
                        if(!in_array($campo, $ignore)) array_push($fields, $tabla.'.'.$campo);
                    }
                }
            }

        }
        foreach($extra as $campo)
        {
            array_push($fields, $campo);
        }

        //\Log::info('Fields Join'.print_r($fields, true));
        return $fields;
    }

    public static function getRandom($size){
        $rand = '';
        for ($i = 0; $i<$size; $i++)
        {
            $rand .= mt_rand(0,9);
        }
        return $rand;
    }

    public static function getVueParam($element,$node,$child)
    {
        $data = isset($element[$node])? (isset($element[$node][$child])? $element[$node][$child]:null):null;
        return $data;
    }
    public static function getParam($element,$node,$child=null)
    {
        if($child)
        {
            $data = isset($element[$node])? (isset($element[$node][$child])? $element[$node][$child]:null):null;
        }
        else{
            $data = isset($element[$node])? $element[$node]:null;
        }
        return $data;
    }


    public static function download($path, $purge=true) {
        $quoted = sprintf('"%s"', addcslashes(basename($path), '"\\'));
        $ext = pathinfo($path, PATHINFO_EXTENSION);

        header('Content-Description: File Transfer');
        //header('Content-Type: application/octet-stream');
        header("Content-Type: application/$ext");
        header('Content-Disposition: attachment; filename='.$quoted);
        header('Content-Transfer-Encoding: binary');
        header('Connection: Keep-Alive');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: '.filesize($path));
        readfile($path); //echo file_get_contents($path);
        if ($purge) unlink($path);
    }

    public static function downloadLaravel($path) {
        $quoted = sprintf('"%s"', addcslashes(basename($path), '"\\'));
        $ext = pathinfo($path, PATHINFO_EXTENSION);

        $headers = array(
            'Content-Description:'=>'File Transfer',
            //'Content-Type: application/octet-stream',
            'Content-Type'=>'application/'.$ext,
            'Content-Disposition'=>'attachment; filename='.$quoted,
            'Content-Transfer-Encoding'=>'binary',
            'Connection'=>'Keep-Alive',
            'Expires'=>'0',
            'Cache-Control'=>'must-revalidate, post-check=0, pre-check=0',
            'Pragma'=>'public',
            'Content-Length'=>filesize($path));

        return response()->download($path,$path, $headers)->deleteFileAfterSend();
    }

    public static function zip($path, $extra=null,$pass="EDM-SIBPHPZIP") {
        $zip = rtrim($path, '.sql').'.zip';
        $za = new \ZipArchive();
        if ($za->open($zip, \ZipArchive::CREATE) !== true)
            throw new \Exception ("Cannot open $zip");
        $za->setPassword($pass);
        $za->addFile($path, basename($path));



        if($extra)
        {
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($extra),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );

            $filesToDelete = array();
            foreach ($files as $name => $file)
            {
                // Skip directories (they would be added automatically)
                if (!$file->isDir())
                {
                    // Get real and relative path for current file
                    $filePath = $file->getRealPath();
                    $extraPrev =
                    $relativePath = substr($filePath, strrpos($extra, '\\', -1)+1);

                    //echo "Real ".$filePath."<br>  Relative: ".$relativePath;
                    // Add current file to archive
                    $za->addFile($filePath, $relativePath);
                    //$filesToDelete[] = $filePath;
                }
            }

            foreach ($filesToDelete as $file)
            {
                //unlink($file);
            }
        }
        $za->close(); // Fatal error: Maximum execution time of 30 seconds exceeded (87 MB)
        unlink($path);
    }

    public static function zipFiles($path, $files) {
        $zip = $path.'.zip';
        $za = new \ZipArchive();
        if ($za->open($zip, \ZipArchive::CREATE) !== true)
            throw new \Exception ("Cannot open $zip");

        for($i = 0; $i < sizeof($files); $i++)
        {
            $za->addFile($files[$i], basename($files[$i]));
        }

        $za->close(); // Fatal error: Maximum execution time of 30 seconds exceeded (87 MB)
        for($i = 0; $i < sizeof($files); $i++)
        {
            unlink($files[$i]);
        }
    }

    public static function getEventId($file)
    {
        return hash_file("md5",$file);
    }

    public static function unzip($file, $route)
    {
        $zip = new \ZipArchive();
        $zip_status = $zip->open($file);

        if ($zip_status === true)
        {
            if (!$zip->extractTo($route))
            {
                $zip->close();
                return true;
            }
        }
        return false;
    }

    public static function getHeaderCSS()
    {
        return "
      

        table
        {
            width: 100%;
            border-collapse: collapse;
            page-break-inside:auto
        }
		table tr    { page-break-inside:avoid; page-break-after:auto }
        table, th, td {
            border: 1px solid #ddd;
        }
        table, td.in {
            border: none;
        }
        .has-child td:first-child
        {
            border-left: 2px solid #d8ad01;
        }
        tr.in{
            background: #eee;
        }
        .header{
            display: block;
            height: 85px;           
            text-align: center;         
        }
        .header > img{
            
        }
        .header > *{
            display:inline-block !important;
            width: auto;
            position: relative !important;
        }
        .info{
			margin-left: 0px;
			
            margin-right: 20px;
            left: 0;
            font-family: 'Wigrum-Regular' !important;
            color: #252544 !important;
        }
        .info h2{
            color: rgb(60,60,60);
        }
        .info p{
            color: rgb(75,75,75);
        }
        .info > *{
            margin: 0;
        }
        .descripcion
        {
            margin: 5px 0;
        } 
        .title{
            text-align:center;
			text-transform: uppercase;
			margin: 10px 0px;
        }
        .firmas{
            bottom: 0;
            width: 100% !important;
            height: auto;
            margin-top: 100px;
            text-align: center;
            page-break-inside: avoid !important;
        }
        .firmas .left-box{
            width: 45%;
            float: left;
        }
        .firmas .right-box{
            width: 45%;
            float: right;
        }
        .firmas .center-box{
            width: 400px;
            margin: 0 auto !important;
        }
        .right-box > span, .left-box > span, .center-box > span{
            display: block;
            margin-top: 25px;
        }
        .descripcion p{
            margin: 0px;
        }
        ";
    }
    public static function getHeader()
    {
        return "
        <div class='header'>
            
            <div class='info'>
            <img src='".public_path()."/images/logo_slogan.png' height='85'/>
            </div>
        </div>";
    }
}
