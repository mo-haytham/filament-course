<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Permission\Models\Role as ModelsRole;

class Role extends ModelsRole
{
    protected $fillable = ["team_id"];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
