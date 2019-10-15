<?php

namespace App\Utils;

use Validator;
use File;
use League\Fractal;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class Serializer
{
    public static function getStandardPaginate($serialized,$results=null,$pagination=false,$hasdata=true)
    {
        if($results)
        {
            $pagination = $results->toArray();
            unset($pagination['data']);
        }
        else $pagination = (isset($serialized['meta']['pagination']))? $serialized['meta']['pagination']:[];

        $data = array("data"=>$serialized['data']);
        if($hasdata == false)
            return $data;
        else if ($pagination)
            return array_merge($pagination, $data);
        else return $data;
    }
    public static function serialize($data, $transformer, $type=null)
    {
        $paginate = $data;
        $resource = new Collection($paginate, $transformer, $type);
        $resource->setPaginator(new IlluminatePaginatorAdapter($data));
        $manager = new Manager();
        return $manager->createData($resource)->toArray();
    }

    public static function serializeArray($data, $transformer, $type=null)
    {
        $paginate = $data;
        $resource = new Collection($paginate, $transformer, $type);
        if(isset($data['current_page']))  $resource->setPaginator(new IlluminatePaginatorAdapter($data));
        $manager = new Manager();
        $created = $manager->createData($resource)->toArray();
        return self::getStandardPaginate($created,$data,isset($data['current_page']),method_exists($data,"total"));
    }

    public static function serializeSingle($data, $transformer, $type=null)
    {
        $paginate = $data;
        $resource = new Item($data, $transformer, $type);
        $manager = new Manager();
        return $manager->createData($resource)->toArray();
    }

    public static function toImage($base64Image)
    {
        $base64Image = str_replace('data:image/png;base64,', '', $base64Image);
        $base64Image = str_replace('data:image/jpg;base64,', '', $base64Image);
        $base64Image = str_replace('data:image/jpeg;base64,', '', $base64Image);
        $base64Image = str_replace('data:image/gif;base64,', '', $base64Image);
        $base64Image = str_replace(' ', '+', $base64Image);
        return base64_decode($base64Image);
    }
}
