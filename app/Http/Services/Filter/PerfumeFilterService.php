<?php

namespace App\Http\Services\Filter;

use App\Http\Const\DefaultConst;
use App\Traits\ReserveProductManagement;

class PerfumeFilterService implements UserProductQuery
{
    use ReserveProductManagement;

    protected array $operator = [
        'gt' => '>',
        'lt' => '<',
        'eq' => '=',
        'nq' => '!=',
    ];

    protected array $safeParam = [
        'name' => ['eq'],
        'quantity' => ['eq'],
        'price' => ['eq', 'gt', 'lt'],
        'volume' => ['lt', 'eq', 'gt'],
        'warranty' => ['eq'],
        'gender' => ['eq'],
        'brand' => ['eq'],
        'category' => ['eq'],
    ];

    protected array $safeSort = [
        'priceAsc' => ['price', 'asc'],
        'priceDesc' => ['price', 'desc'],
        'sold' => ['sold', 'asc'],
        'newest' => ['created_at', 'asc'],
    ];

    private array $eloquentQuery;

    private array $urlQuery;

    private string $sort;

    public function queryRetriever($query)
    {
        $this->urlQuery = $query;

        return $this;
    }

    public function sanitize()
    {
        // delete warranty and sort from the array
        foreach ($this->urlQuery as $key => $value) {
            if ($key == 'sort' and ! is_array($value) and isset($value)) {
                foreach ($this->safeSort as $flag => $item) {
                    if ($flag == $value) {
                        $this->sort = $item;
                        break;
                    }
                }
            }
        }

        return $this;
    }

    public function eloquentQueryBuilder()
    {
        // build the eloquent query by changing url query to array suitable for eloquent
        $eloquentQuery = [];
        foreach ($this->safeParam as $param => $operators) {
            $urlParam = $this->urlQuery[$param] ?? null;
            if (! $urlParam) {
                continue;
            }
            if (! is_array($urlParam)) {
                continue;
            }
            $tmpEloquentQuery = [];
            foreach ($operators as $operator) {
                $urlValue = $urlParam[$operator] ?? null;
                if (! $urlValue) {
                    continue;
                }
                if (is_array($urlValue) and count($urlValue) > 0) {
                    // this if statement is for when the array with operator key has array in value for example category[eq][0]=test&category[eq][1]=test2
                    foreach ($urlValue as $value) {
                        if (count($eloquentQuery) == 0) {
                            $tmpEloquentQuery[] = [[$param, $this->operator[$operator], $value]];
                        } else {
                            foreach ($eloquentQuery as $item) {
                                // single where
                                $tmpEloquentQuery[] = array_merge($item, [[$param, $this->operator[$operator], $value]]);
                            }
                        }
                    }
                } else {
                    // this else statement is for when the array with operator key doesnt have  array in value for example category[eq]=test
                    // build the array and template
                    if (count($eloquentQuery) == 0) {
                        // build array and template
                        if ($param == 'warranty') {
                            $tmpEloquentQuery[] = [[$param, '!=', null]];
                        } else {
                            $tmpEloquentQuery[] = [[$param, $this->operator[$operator], $urlValue]];
                        }
                    } else {
                        foreach ($eloquentQuery as $item) {
                            if ($param == 'warranty') {
                                $tmpEloquentQuery[] = array_merge($item, [[$param, '!=', null]]);
                            } else {
                                $tmpEloquentQuery[] = array_merge($item, [[$param, $this->operator[$operator], $urlValue]]);
                            }

                        }
                    }
                }
            }
            $eloquentQuery = [];
            $eloquentQuery = $tmpEloquentQuery;
            $tmpEloquentQuery = [];
        }
        $this->eloquentQuery = $eloquentQuery;

        return $this;
    }

    public function get($query)
    {
        $eloquentQuery = $this->eloquentQuery;
        if (! $eloquentQuery or count($eloquentQuery) == 0) {
            return [];
        }
        // for begin query we only use where in eloquent
        $query = $this->beginQuery($query, $eloquentQuery[0]);
        // for continue query we only use orWhere in eloquent
        $query = $this->continueQuery($query, $eloquentQuery);
        $query = $this->sortQuery($query);
        $query->with(['category', 'brand']);
        $databaseResult = $query->paginate(DefaultConst::PAGINATION_NUMBER);

        return $this->availableProductsAmount($databaseResult);
    }

    private function beginQuery($query, $eloquentQuery)
    {
        $query = $this->relationalQuery($query, $eloquentQuery);
        $eloquentQuery = $this->deleteRelationalQuery($eloquentQuery);
        $query->where($eloquentQuery);

        return $query;
    }

    private function relationalQuery($query, $eloquentQuery)
    {
        foreach ($eloquentQuery as $key => $item) {
            switch ($item[0]) {
                case 'brand':
                    $query = $this->brandQuery($query, $item[2]);
                    break;
                case 'category':
                    $query = $this->categoryQuery($query, $item[2]);
                    break;
            }
        }

        return $query;
    }

    private function continueQuery($query, $eloquentQueries)
    {
        //  $eloquentQuery[0] is already been processed in beginQuery() method in $query variable with eloquent where method
        unset($eloquentQueries[0]);
        if (count($eloquentQueries) == 0) {
            return $query;
        }
        foreach ($eloquentQueries as $eloquentQuery) {
            [
                'category' => $category,
                'brand' => $brand
            ] = $this->relationalQueryName($eloquentQuery);
            $eloquentQuery = $this->deleteRelationalQuery($eloquentQuery);
            $query->orWhere(function ($query) use ($eloquentQuery) {
                $query->where($eloquentQuery);
            });
            if ($category !== null) {
                $query = $this->categoryQuery($query, $category);
            }
            if ($brand !== null) {
                $query = $this->brandQuery($query, $brand);
            }
        }

        return $query;
    }

    private function sortQuery($query)
    {
        if (isset($this->sort)) {
            $query->orderBy($this->sort[0], $this->sort[1]);
        }

        return $query;
    }

    private function availableProductsAmount($result)
    {
        foreach ($result as $item) {
            $item->quantity = $item->quantity - $this->getReservedProduct($item->id, 'perfume');
        }

        return $result;
    }

    private function deleteRelationalQuery($eloquentQuery)
    {
        foreach ($eloquentQuery as $key => $item) {
            if ($item[0] == 'brand' || $item[0] == 'category') {
                unset($eloquentQuery[$key]);
            }
        }

        return $eloquentQuery;
    }

    private function brandQuery($query, $value)
    {
        $query->whereHas('brand', function ($query) use ($value) {
            $query->where('name', $value);
        });

        return $query;
    }

    private function categoryQuery($query, mixed $value)
    {
        $query->whereHas('category', function ($query) use ($value) {
            $query->where('name', $value);
        });

        return $query;
    }

    private function relationalQueryName($item)
    {
        foreach ($item as $inKey => $value) {
            if ($value[0] == 'category') {
                $category = $value[2];
            }
            if ($value[0] == 'brand') {
                $brand = $value[2];
            }
        }

        return [
            'category' => $category ?? null,
            'brand' => $brand ?? null,
        ];
    }
}
