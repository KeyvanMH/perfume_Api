<?php

namespace App\Http\Services\Filter;

interface UserProductQuery
{
    public function queryRetriever($query);

    public function sanitize();

    public function eloquentQueryBuilder();

    public function get($query);
}
