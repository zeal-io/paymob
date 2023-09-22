<?php

namespace Zeal\Paymob\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Zeal\PaymentFramework\Interfaces\IntegrationKeyInterface;

class IntegrationKey extends Model implements IntegrationKeyInterface
{
    protected $table = 'paymob_integration_keys';

    protected $guarded = ['id'];


    public function getUsernameAttribute()
    {
        return $this->attributes['merchant_code'];
    }

    public function getPasswordAttribute()
    {
        return $this->attributes['api_key'];
    }
}
