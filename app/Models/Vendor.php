<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vendor extends Model
{
    protected $fillable = ['name', 'contact_person', 'phone', 'email', 'address', 'website', 'notes'];

    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }

    public function softwareLicenses(): HasMany
    {
        return $this->hasMany(SoftwareLicense::class);
    }
}
