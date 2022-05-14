<?php

namespace Auezov\Permission\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';

    public function modules(): BelongsToMany
    {
        return $this->belongsToMany(Module::class, 'role_has_modules', 'role_id', 'module_id');
    }

    public function users(): MorphToMany
    {
        return $this->morphedByMany(getModelForGuard($this->attributes['guard_name']), 'model', 'model_has_roles', 'role_id', 'model_id');
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_has_permissions', 'role_id', 'permission_id');
    }
}
