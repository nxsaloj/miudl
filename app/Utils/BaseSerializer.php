<?php

namespace App\Utils;

use Illuminate\Database\Eloquent\Model;
use Validator;
use File;
use League\Fractal;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class BaseSerializer extends Fractal\TransformerAbstract
{
    private $only = null;
    private $ignore = null;
    function __construct($only=null, $ignore=null) {
        $this->only = $only;
        $this->ignore = $ignore;
    }
    public function getSerialized($data){
        $only = $this->only;
        $ignore = $this->ignore;
        if($only || $ignore)
        {
            $transform = array();
            if($only)
            {
                //foreach($data as $key => $field)
                $keys = array_keys($data);
                for($i = 0; $i < sizeof($keys); $i++)
                {
                    $key = $keys[$i];
                    $field = $data[$key];
                    if(in_array($key,$only))
                    {
                        $transform = array_merge($transform, array($key=>$field));
                    }
                }
            }
            else
            {
                //foreach($data as $key => $field)
                $keys = array_keys($data);
                for($i = 0; $i < sizeof($keys); $i++)
                {
                    $key = $keys[$i];
                    $field = $data[$key];
                    if(!in_array($key,$ignore))
                    {
                        $transform = array_merge($transform, array($key=>$field));
                    }
                }
            }
        }
        else $transform = $data;

        return $transform;
    }
}
