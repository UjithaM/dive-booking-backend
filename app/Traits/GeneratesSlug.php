<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait GeneratesSlug
{
    protected function generateUniqueSlug(string $name, string $table, string $tenantId, ?string $ignoreId = null): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        $query = \DB::table($table)
            ->where('tenant_id', $tenantId)
            ->where('slug', $slug);

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        while ($query->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;

            $query = \DB::table($table)
                ->where('tenant_id', $tenantId)
                ->where('slug', $slug);

            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
        }

        return $slug;
    }
}
