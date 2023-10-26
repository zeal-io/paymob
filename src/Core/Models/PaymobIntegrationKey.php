<?php

namespace Zeal\Paymob\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Zeal\PaymentFramework\Interfaces\IntegrationKeyInterface;
use Zeal\Paymob\Core\Traits\HasIntegrationKey;

class PaymobIntegrationKey extends Model implements IntegrationKeyInterface
{
    use HasIntegrationKey;

    protected $guarded = ['id'];
}
