<?php

namespace App\Http\Action\Filter;

use App\Http\Const\DefaultConst;
use App\Models\Perfume;

class UserProductQuery {

    //todo change this to interface
    protected array $urlQuery;
    protected array $sort;
    protected array $arrayForQuery;


    protected array $operator = [];
    protected array $safeParam = [];
    protected array $safeSort = [];

    public function queryRetriever($query) {
        return $this;
    }
    public function sanitize() {
        return $this;
    }
    public function arrayBuilder() {
        return $this;
    }

    public function queryBuilder($query){
        return $query->paginate(DefaultConst::PAGINATION_NUMBER);
    }
}
