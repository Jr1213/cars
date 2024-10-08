<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Car extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'year', 'price', 'type', 'company_id'];


    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
