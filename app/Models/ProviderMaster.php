<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProviderMaster extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'provider_type',
        'api_config',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'api_config' => 'array',
        'status' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid();
            }
        });
    }

    /**
     * Get the provider mappings for this provider.
     */
    public function providerMappings()
    {
        return $this->hasMany(EntityProviderMapping::class, 'provider_id');
    }

    /**
     * Get the message requests for this provider.
     */
    public function messageRequests()
    {
        return $this->hasMany(MessageRequest::class, 'provider_id');
    }
}
