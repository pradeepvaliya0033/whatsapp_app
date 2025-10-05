<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EntityMaster extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
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
     * Get the provider mappings for this entity.
     */
    public function providerMappings()
    {
        return $this->hasMany(EntityProviderMapping::class, 'entity_id');
    }

    /**
     * Get the message requests for this entity.
     */
    public function messageRequests()
    {
        return $this->hasMany(MessageRequest::class, 'entity_id');
    }
}
