<?php

namespace View;

class VStatus extends BaseView
{
    public function showStatus(int $status_code): void
    {
        if ($status_code === 403) {
            http_response_code(403);
        }
    }
}
