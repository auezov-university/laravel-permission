<?php

namespace Auezov\Permission\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Module extends Model
{
    use HasFactory;

    protected $table = 'modules';

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'module_has_permissions', 'module_id', 'permission_id');
    }
}
