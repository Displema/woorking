<?php

namespace View;

class VStatus
{
    public function __construct()
    {
    }

    public function showStatus(int $status_code): void
    {
        if ($status_code === 403) {
            http_response_code(403);
        }
    }
}
