<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HospitalBankaccount extends Model
{

    const ACCOUNT_TYPE_SAVINGS = 1;
    const ACCOUNT_TYPE_CURRENT = 2;
    const ACCOUNT_TYPE_RECURRING_DEPOSIT = 3;
    const ACCOUNT_TYPE_FIXED_DEPOSIT = 4;

    protected $fillable = [
        'bank_name',
        'account_no',
        'date',
        'opening_balance',
        'account_type',
        'branch',
        'status',
        'created_by',
    ];
    protected $table = 'hospital_bankaccount';

    public static function rules()
    {
        return [
            'bank_name' => 'required',
            'account_no' => 'required',
            'date' => 'required',
            'opening_balance' => 'required',
            'account_type' => 'required',
            'branch' => 'required',
        ];
    }

    public function setData($request)
    {
        $this->bank_name = $request->get('bank_name');
        $this->account_no = $request->get('account_no');
        $this->date = date("Y-m-d", strtotime($request->get('date')));
        $this->opening_balance = $request->get('opening_balance');
        $this->account_type = $request->get('account_type');
        $this->branch = $request->get('branch');
        $this->created_by = \Auth::user()->id;
        return $this;
    }

    public static function getTypeOptions($id = null)
    {
        $list = [
            self::ACCOUNT_TYPE_SAVINGS => 'Savings Account',
            self::ACCOUNT_TYPE_CURRENT => 'Current Account',
            self::ACCOUNT_TYPE_RECURRING_DEPOSIT => 'Recurring Deposit',
            self::ACCOUNT_TYPE_FIXED_DEPOSIT => 'Fixed Deposit'
        ];

        if ($id === null) {
            return $list;
        }

        if (isset($list[$id]))
            return $list[$id];

        return $id;
    }

    public static function getRoutesArray()
    {
        return [
            'Laralum::admin.hospital_bank_account',
            'Laralum::admin.hospital_bank_account.add',
            'Laralum::admin.hospital_bank_account.edit',
            'Laralum::hospital_bankaccount.delete'
        ];
    }
}
