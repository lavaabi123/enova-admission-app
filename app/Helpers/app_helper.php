<?php

if (! function_exists('status_badge')) {
    function status_badge(string $status): string
    {
        $map = [
            'pending'      => ['warning', 'Pending'],
            'under_review' => ['info',    'Under Review'],
            'approved'     => ['success', 'Approved'],
            'rejected'     => ['danger',  'Rejected'],
        ];
        [$cls, $label] = $map[$status] ?? ['secondary', ucfirst($status)];
        return "<span class=\"badge bg-{$cls}\">{$label}</span>";
    }
}

if (! function_exists('format_date')) {
    function format_date(string $date, string $format = 'd M Y'): string
    {
        return date($format, strtotime($date));
    }
}

