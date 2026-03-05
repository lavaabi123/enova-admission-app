<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;

class FileController extends BaseController
{
    public function serve(string $subfolder, string $filename): ResponseInterface
    {
        // Only logged-in users can access files
        if (! session()->get('student_logged_in') && ! session()->get('admin_logged_in')) {
            return $this->response->setStatusCode(403)->setBody('Forbidden');
        }

        // Sanitize — prevent path traversal
        $subfolder = basename($subfolder);
        $filename  = basename($filename);

        $filePath = WRITEPATH . 'uploads/' . $subfolder . '/' . $filename;

        if (! is_file($filePath)) {
            return $this->response->setStatusCode(404)->setBody('File not found');
        }

        // Detect mime type
        $finfo    = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($filePath);

        // Only allow safe mime types
        $allowed = [
            'image/jpeg', 'image/jpg', 'image/png',
            'application/pdf',
        ];

        if (! in_array($mimeType, $allowed)) {
            return $this->response->setStatusCode(403)->setBody('Forbidden file type');
        }

        return $this->response
            ->setHeader('Content-Type', $mimeType)
            ->setHeader('Content-Length', (string) filesize($filePath))
            ->setHeader('Cache-Control', 'private, max-age=86400')
            ->setBody(file_get_contents($filePath));
    }
}
