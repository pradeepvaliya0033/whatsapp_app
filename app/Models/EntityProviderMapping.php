<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EntityProviderMapping extends Model
{
    use HasFactory;

    protected $fillable = [
        'entity_id',
        'provider_id',
        'usage_type',
        'is_default',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_default' => 'boolean',
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
     * Get the entity that owns this mapping.
     */
    public function entity()
    {
        return $this->belongsTo(EntityMaster::class, 'entity_id');
    }

    /**
     * Get the provider for this mapping.
     */
    public function provider()
    {
        return $this->belongsTo(ProviderMaster::class, 'provider_id');
    }

    /**
     * Get the message requests for this mapping.
     */
    public function messageRequests()
    {
        return $this->hasMany(MessageRequest::class, 'provider_id', 'provider_id');
    }
}
