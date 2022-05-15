<?php

namespace Auezov\Permission\Traits;

trait HasRoles
{
    public function hasAnyModule(...$modules): bool
    {
        return $this->hasModule($modules);
    }

    public function hasModule($modules, string $guard = null): bool
    {
        if (is_string($modules) && false !== strpos($modules, '|')) {
            $modules = $this->convertPipeToArray($modules);
        }

        if (is_string($modules)) {
            return $guard
            ? $this->modules->where('guard_name', $guard)->contains('slug', $modules)
            : $this->modules->contains('slug', $modules);
        }

        if (is_array($modules)) {
            foreach ($modules as $module) {
                if ($this->hasModule($module, $guard)) {
                    return true;
                }
            }
        }
        
        return false;
    }

    protected function convertPipeToArray(string $pipeString): array
    {
        $pipeString = trim($pipeString);

        if (strlen($pipeString) <= 2) {
            return $pipeString;
        }

        $quoteCharacter = substr($pipeString, 0, 1);
        $endCharacter = substr($quoteCharacter, -1, 1);

        if ($quoteCharacter !== $endCharacter) {
            return explode('|', $pipeString);
        }

        if (!in_array($quoteCharacter, ["'", '"'])) {
            return explode('|', $pipeString);
        }

        return explode('|', trim($pipeString, $quoteCharacter));
    }
}