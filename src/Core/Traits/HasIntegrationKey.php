<?php

namespace Zeal\Paymob\Core\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasIntegrationKey
{
    public function initializeHasIntegrationKey(): void
    {
        $this->table = config('paymob.integration_key.table_name');
    }

    protected function username(): Attribute
    {
        $usernameDatabaseColumn = config('paymob.integration_key.columns.username');
        return Attribute::make(
            get: fn($value) => $this->attributes[$usernameDatabaseColumn],
            set: fn($value) => $this->attributes[$usernameDatabaseColumn] = $value,
        );
    }

    protected function password(): Attribute
    {
        $passwordDatabaseColumn = config('paymob.integration_key.columns.password');
        return Attribute::make(
            get: fn($value) => $this->attributes[$passwordDatabaseColumn],
            set: fn($value) => $this->attributes[$passwordDatabaseColumn] = $value,
        );
    }
}
