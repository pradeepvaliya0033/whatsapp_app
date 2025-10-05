<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MessageRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'transition_number',
        'entity_id',
        'provider_id',
        'type',
        'message_config',
        'message_count',
        'status',
        'request',
        'response',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'message_config' => 'array',
        'request' => 'array',
        'response' => 'array',
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
     * Get the entity that owns this message request.
     */
    public function entity()
    {
        return $this->belongsTo(EntityMaster::class, 'entity_id');
    }

    /**
     * Get the provider for this message request.
     */
    public function provider()
    {
        return $this->belongsTo(ProviderMaster::class, 'provider_id');
    }
}
