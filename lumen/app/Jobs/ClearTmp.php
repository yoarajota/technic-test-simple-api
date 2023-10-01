<?php

namespace App\Jobs;

class ClearTmp extends Job
{

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private $path)
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        unlink($this->path);
    }
}
