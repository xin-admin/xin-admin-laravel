<?php

namespace Modules\SystemAgent\Models;

use Illuminate\Database\Eloquent\Model;

class AgentModel extends Model
{
    protected $table = 'agents';

    protected $fillable = [
        'namespace',
        'name',
        'icon',
        'description',
        'tags',
        'enabled',
    ];

    protected $casts = [
        'tags' => 'array',
        'enabled' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
