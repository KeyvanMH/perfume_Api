<?php

namespace App\Http\Action;

use App\Models\Perfume;

class UserQuery {

    protected array $urlQuery;
    protected array $sort;

    protected array $operator = [
        'gt' => '>',
        'lt' => '<',
        'eq' => '=',
        'nq' => '!='
    ];
    protected array $safeParam = [
        'name' => ['eq'],
        'quantity' => ['eq'],
        'price' => ['eq','gt','lt'],
        'volume' => ['eq','gt','lt'],
        'warranty' => ['eq'],
        'gender' => ['eq'],
        'brand' => ['eq'],
        'category' => ['eq'],
    ];
    protected array $safeSort = [
        'priceAsc' => ['price','asc'],
        'priceDesc' => ['price','desc'],
        'sold' => ['sold','asc'],
        'newest' => ['created_at','asc'],
    ];
    public function __Construct($query){
        $this->urlQuery = $query;
    }
    public function sanitize() {
        //delete warranty and sort from the array
        foreach($this->urlQuery as $key => $value){
            if ($key == 'sort' and !is_array($value) and isset($value)){
                foreach ($this->safeSort as $flag => $item){
                    if($flag == $value){
                        $this->sort = $item;
                        break;
                    }
                }
            }
        }
    }
    public function arrayBuilder() {
        $lastTemplate = [];
        foreach ($this->safeParam as $param => $operators){
            $urlParam = $this->urlQuery[$param]??NULL;
            if(!$urlParam){
                continue;
            }
            if(!is_array($urlParam)){
                continue;
            }
            $template = [];
            foreach ($operators as $operator){
                $urlValue = $urlParam[$operator]??NULL;
                if(!$urlValue){
                    continue;
                }
                if(is_array($urlValue) and count($urlValue) > 0){
                    //this if statement is for when the array with operator key has array in value for example category[eq][0]=test&category[eq][1]=test2
                    foreach($urlValue as $value){
                        if(count($lastTemplate) == 0) {
                            $template[] = [[$param, $this->operator[$operator], $value]];
                        }else{
                            foreach ($lastTemplate as $item){
                                //single where
                                    $template[] = array_merge($item, [[$param, $this->operator[$operator], $value]]);
                            }
                        }
                    }
                }else{
                    //this else statement is for when the array with operator key doesnt have  array in value for example category[eq]=test
                    //build the array and template
                    if(count($lastTemplate) == 0) {
                        //build array and template
                        if($param == 'warranty'){
                            $template[] = [[$param,'!=',NULL]];
                        }else{
                            $template[] = [[$param,$this->operator[$operator],$urlValue]];
                        }
                    }else{
                        foreach ($lastTemplate as $item){
                            if($param == 'warranty'){
                                $template[] = array_merge($item,[[$param,'!=',NULL]]);
                            }else{
                                $template[] = array_merge($item,[[$param, $this->operator[$operator], $urlValue]]);
                            }

                        }
                    }
                }
            }
            $lastTemplate = [];
            $lastTemplate = $template;
            $template = [];

        }
        return $lastTemplate;
    }

    public function queryBuilder($array){
        if(!$array or !is_array($array) or count($array) == 0){
            return [];
        }
        $query = Perfume::query();
        //TODO is_active
//        $query->where('is_active',1);
        foreach($array[0] as $key => $item){
            if ($item[0] == 'brand' ){
                $query->whereHas('brand' , function($query)use($item){
                    $query->where('name',$item[2]);
                });
                unset($array[0][$key]);
            }elseif ($item[0] == 'category'){
                $query->whereHas('category' , function($query)use($item){
                    $query->where('name',$item[2]);
                });
                unset($array[0][$key]);
            }
        }
        $query->where($array[0]);
        unset($array[0]);
        $brand = null;
        $category = null;
        if(count($array) > 0){
            foreach($array as $outKey => $item){
                foreach($item as $inKey => $value){
                    if($value[0] == 'category'){
                        $category = $value[2];
                        unset($array[$outKey][$inKey]);
                    }
                    if($value[0] == 'brand'){
                        $brand = $value[2];
                        unset($array[$outKey][$inKey]);
                    }
                }
                $query->orWhere($array[$outKey]);
                if ($category !== null){
                    $query->whereHas('category',function ($query) use ($category){
                        $query->where('name',$category);
                    });
                }
                if ($brand !== null){
                    $query->whereHas('brand',function($query)use($brand){
                        $query->where('name',$brand);
                    });
                }
            }
        }
        if(isset($this->sort)) {
            $query->orderBy($this->sort[0],$this->sort[1]);
        }
        $query->with(['category','brand']);
        return $query->paginate(15);
    }


}
