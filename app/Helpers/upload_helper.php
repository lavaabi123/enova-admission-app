<?php

if (! function_exists('upload_certificate')) {
    // Upload a certificate file (PDF/JPG/PNG), max 2MB 
    function upload_certificate(string $inputName, string $subfolder = 'certificates'): array
    {
        $request = \Config\Services::request();
        $file    = $request->getFile($inputName);

        // No file uploaded
        if (! $file || ! $file->isValid() || $file->getError() === UPLOAD_ERR_NO_FILE) {
            return ['success' => false, 'filename' => '', 'error' => 'No file uploaded.'];
        }

        // Validate mime type
        $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
        if (! in_array($file->getMimeType(), $allowedMimes)) {
            return ['success' => false, 'filename' => '', 'error' => 'Only JPG, PNG, or PDF files are allowed.'];
        }

        // Validate size (2MB max)
        if ($file->getSize() > 2 * 1024 * 1024) {
            return ['success' => false, 'filename' => '', 'error' => 'File size must be under 2MB.'];
        }

        $newName    = $file->getRandomName();
        $uploadPath = WRITEPATH . 'uploads/' . $subfolder;

        if (! is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $file->move($uploadPath, $newName);

        return ['success' => true, 'filename' => $subfolder . '/' . $newName, 'error' => ''];
    }
}

if (! function_exists('upload_photo')) {
    // Upload profile photo (JPG/PNG), max 1MB
    function upload_photo(string $inputName): array
    {
        $request = \Config\Services::request();
        $file    = $request->getFile($inputName);

        // No file uploaded
        if (! $file || ! $file->isValid() || $file->getError() === UPLOAD_ERR_NO_FILE) {
            return ['success' => false, 'filename' => '', 'error' => 'No photo uploaded.'];
        }

        // Validate mime type (images only)
        $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (! in_array($file->getMimeType(), $allowedMimes)) {
            return ['success' => false, 'filename' => '', 'error' => 'Only JPG or PNG photos are allowed.'];
        }

        // Validate size (1MB max)
        if ($file->getSize() > 1 * 1024 * 1024) {
            return ['success' => false, 'filename' => '', 'error' => 'Photo must be under 1MB.'];
        }

        $newName = $file->getRandomName();
        $path    = WRITEPATH . 'uploads/photos/';

        if (! is_dir($path)) {
            mkdir($path, 0755, true);
        }

        $file->move($path, $newName);

        return ['success' => true, 'filename' => 'photos/' . $newName, 'error' => ''];
    }
}
