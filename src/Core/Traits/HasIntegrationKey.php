<?php

namespace Zeal\Paymob\Core\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasIntegrationKey
{
    public function initializeHasIntegrationKey(): void
    {
        $this->table = config('paymob.integration_key.table_name');
    }

    protected function getMerchantCodeAttribute()
    {
        $usernameDatabaseColumn = config('paymob.integration_key.columns.merchant_code');

        return $this->attributes[$usernameDatabaseColumn];
    }

    protected function getApiKeyAttribute()
    {
        $passwordDatabaseColumn = config('paymob.integration_key.columns.api_key');

        return $this->attributes[$passwordDatabaseColumn];
    }
}
