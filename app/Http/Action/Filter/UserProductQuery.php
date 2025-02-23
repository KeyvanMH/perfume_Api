<?php

namespace App\Http\Action\Filter;

use App\Http\Const\DefaultConst;
use App\Models\Perfume;

interface UserProductQuery {

    public function queryRetriever($query);
    public function sanitize();
    public function eloquentQueryBuilder();
    public function get($query);
}
