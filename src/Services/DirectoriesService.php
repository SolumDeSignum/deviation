<?php

declare(strict_types=1);

namespace SolumDeSignum\Deviation\Services;

use Illuminate\Support\Facades\File;

class DirectoriesService
{
    public array $directories = [
        'storage/deviation/pdf',
        'storage/deviation/log'
    ];

    public function create(string $directory): bool
    {
        return File::makeDirectory($directory, 0775, true, true);
    }

    public function exist(string $directory): bool
    {
        return File::isDirectory($directory);
    }
}
