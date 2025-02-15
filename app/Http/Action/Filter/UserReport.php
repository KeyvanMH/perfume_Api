<?php

namespace App\Http\Action\Filter;

use App\Http\Const\DefaultConst;

class UserReport {
    private array $urlQuery ;
    private array $operators = [
        'eq' => '='
    ];
    private array $filterParams = [
        'status' => ['eq'],
        'role' => ['eq'],
        'firstName' => ['eq'],
        'email' => ['eq'],
        'hasEmail' => ['eq'],
        'lastName' => ['eq'],
        'isEmailVerified'=> ['eq']
    ];
    private array $columnName = [
        'isEmailVerified' => 'email_verified_at',
        'status' => 'deleted_at',
        'email' => 'email',
        'hasEmail' => 'email',
        'role' => 'role',
        'firstName' => 'first_name',
        'lastName' => 'last_name'
    ];
    private array $dataType = [
          'isEmailVerified' => 'boolean',
          'status' => 'boolean',
          'email' => 'string',
          'hasEmail' => 'boolean',
          'role' => 'string',
          'firstName' => 'string',
          'lastName' => 'string',
    ];

    public function urlQueryRetriever($urlQuery) {
        $this->urlQuery = $urlQuery;
        return $this;
    }

    public function get($query) {
        foreach ($this->filterParams as $key => $value) {
            if (isset($this->urlQuery[$key]) && is_array($this->urlQuery[$key])) {
                foreach($value as $operator){
                    if(isset($this->urlQuery[$key][$operator])){
                        //cant use dataType with variables
                        switch ($this->dataType[$key]){
                            case 'string':
                                $query->where($this->columnName[$key],$this->operators[$operator],(string)$this->urlQuery[$key][$operator]);
                                break;
                            case 'boolean':
                                $booleanValue = strtolower($this->urlQuery[$key][$operator]);
                                if(empty($booleanValue) || $booleanValue == 'null' || $booleanValue == null || $booleanValue == 'false' || $booleanValue == false){
                                    $query->where($this->columnName[$key],$this->operators[$operator],null);
                                    break;
                                }
                                $query->where($this->columnName[$key],$this->operators[$operator],true);
                                break;
                            default:
                                $query->where($this->columnName[$key],$this->operators[$operator],$this->urlQuery[$key][$operator]);
                        }
                    }
                }
            }
        }
        return $query->paginate(DefaultConst::PAGINATION_NUMBER);
    }

}
