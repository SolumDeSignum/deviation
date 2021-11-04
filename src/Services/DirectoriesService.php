<?php

declare(strict_types=1);

namespace SolumDeSignum\Deviation\Services;

use Illuminate\Support\Facades\Storage;

class DirectoriesService
{
    public array $directories = [
        'deviation/pdf',
        'deviation/log'
    ];

    public function create(string $directory): bool
    {
        return Storage::makeDirectory($directory);
    }

    public function exist(string $directory): bool
    {
        return Storage::exists($directory);
    }
}
