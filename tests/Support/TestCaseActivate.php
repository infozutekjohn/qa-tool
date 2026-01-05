<?php

namespace Tests\Support;

use Illuminate\Support\Facades\Log;

trait TestCaseActivate
{
    protected function shouldRunGroup(string $group): bool
    {
        $selected = getenv('SELECTED_GROUPS');

        // If runner did not pass any group, allow all tests
        if (!$selected) {
            return true;
        }

        $groups = array_map('trim', explode(',', $selected));

        return in_array($group, $groups, true);
    }

    protected function requireGroup(string $group): void
    {
        if (!$this->shouldRunGroup($group)) {
            Log::info('[SKIPPED BY GROUP GUARD]', [
                'required_group' => $group,
                'selected_groups' => getenv('SELECTED_GROUPS'),
                'test' => static::class . '::' . $this->name(),
            ]);

            $this->markTestSkipped(
                "Skipped by UI selection. Required group '{$group}', selected: " . getenv('SELECTED_GROUPS')
            );
        }
    }
}
