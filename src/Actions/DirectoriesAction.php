<?php

declare(strict_types=1);

namespace SolumDeSignum\Deviation\Actions;

use SolumDeSignum\Deviation\Services\DirectoriesService;

use function storage_path;

class DirectoriesAction
{
    private DirectoriesService $directoriesService;

    public function __construct()
    {
        $this->directoriesService = new DirectoriesService();
    }

    public function run(): void
    {
        foreach ($this->directoriesService->directories as $directory) {
            $directory = storage_path($directory);

            if (! $this->directoriesService->exist($directory)) {
                $this->directoriesService->create($directory);
            }
        }
    }
}
