<?php

namespace Zeal\Paymob\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Zeal\PaymentFramework\Interfaces\IntegrationKeyInterface;

class PaymobIntegrationKey extends Model implements IntegrationKeyInterface
{
    protected $guarded = ['id'];
}
