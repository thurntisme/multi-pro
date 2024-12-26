<?php
global $api_url, $payload, $method;
$slug = str_replace('file-manager/', '', $api_url);
switch ($slug) {
  case 'upload':
    if ($method === 'GET') {
      sendResponse("success", 200, "Item retrieved successfully");
    } elseif ($method === 'POST') {
      $file = $payload['file'] ?? null;
      handleUploadFile($file);
    } else {
      sendResponse("error", 405, "Method Not Allowed");
    }
    break;

  default:
    sendResponse("error", 404, "Endpoint not found");
    break;
}

/**
 * Handles file upload securely.
 *
 * @param array|null $file Uploaded file information from the request payload.
 */
function handleUploadFile($file)
{
  if (!$file || !is_array($file)) {
    sendResponse("error", 400, "No file provided or invalid file format");
    return;
  }

  // Define the upload directory
  $uploadDir = DIR . '/assets/uploads/';

  // Ensure the upload directory exists
  if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
    sendResponse("error", 500, "Failed to create upload directory");
    return;
  }

  // Extract file details
  $fileName = basename($file['name']);
  $fileTmpPath = $file['tmp_name'];
  $fileSize = $file['size'];
  $fileError = $file['error'];

  // Check for upload errors
  if ($fileError !== UPLOAD_ERR_OK) {
    sendResponse("error", 400, "File upload error: " . getUploadErrorMessage($fileError));
    return;
  }

  // Validate file size (limit: 2MB)
  $maxFileSize = 2 * 1024 * 1024; // 2 MB
  if ($fileSize > $maxFileSize) {
    sendResponse("error", 400, "File size exceeds the limit of 5MB");
    return;
  }

  // Validate file type (allow only images as an example)
  $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
  $fileMimeType = mime_content_type($fileTmpPath);
  if (!in_array($fileMimeType, $allowedMimeTypes)) {
    sendResponse("error", 400, "Invalid file type. Only images are allowed");
    return;
  }

  // Generate a unique file name to prevent overwriting
  $uniqueFileName = uniqid('upload_', true) . '_' . $fileName;

  // Define the target path
  $targetPath = $uploadDir . $uniqueFileName;

  // Move the uploaded file to the target directory
  if (!move_uploaded_file($fileTmpPath, $targetPath)) {
    sendResponse("error", 500, "Failed to save the uploaded file");
    return;
  }

  // Send success response
  sendResponse("success", 200, "File uploaded successfully", [
    'file_name' => $uniqueFileName,
    'file_size' => $fileSize,
    'file_type' => $fileMimeType
  ]);
}

/**
 * Returns a human-readable message for file upload errors.
 *
 * @param int $errorCode Error code from the file upload process.
 * @return string
 */
function getUploadErrorMessage($errorCode)
{
  $errors = [
    UPLOAD_ERR_INI_SIZE => "The uploaded file exceeds the upload_max_filesize directive in php.ini",
    UPLOAD_ERR_FORM_SIZE => "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",
    UPLOAD_ERR_PARTIAL => "The uploaded file was only partially uploaded",
    UPLOAD_ERR_NO_FILE => "No file was uploaded",
    UPLOAD_ERR_NO_TMP_DIR => "Missing a temporary folder",
    UPLOAD_ERR_CANT_WRITE => "Failed to write file to disk",
    UPLOAD_ERR_EXTENSION => "File upload stopped by a PHP extension"
  ];

  return $errors[$errorCode] ?? "Unknown upload error";
}
