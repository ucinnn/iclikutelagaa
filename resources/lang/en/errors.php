<?php

return [
    // General Errors
    'general' => [
        'title' => 'An Error Occurred',
        'message' => 'Sorry, an error occurred. Please try again.',
        'not_found' => 'Data not found.',
        'unauthorized' => 'You do not have access to this page.',
        'forbidden' => 'Access denied.',
        'server_error' => 'A server error occurred. Please contact the administrator.',
    ],

    // Validation Errors
    'validation' => [
        'required' => 'The :attribute field is required.',
        'email' => 'Invalid email format.',
        'min' => 'The :attribute must be at least :min characters.',
        'max' => 'The :attribute may not be greater than :max characters.',
        'unique' => 'The :attribute has already been taken.',
        'confirmed' => 'The :attribute confirmation does not match.',
        'numeric' => 'The :attribute must be a number.',
        'in' => 'The selected :attribute is invalid.',
        'file' => 'The file must be a file.',
        'mimes' => 'The file must be a file of type: :values.',
        'max_file' => 'The file may not be greater than :max kilobytes.',
    ],

    // Authentication Errors
    'auth' => [
        'failed' => 'These credentials do not match our records.',
        'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',
        'logged_out' => 'You have been logged out.',
        'session_expired' => 'Your session has expired. Please login again.',
        'unauthorized' => 'You must be logged in first.',
        'password_incorrect' => 'The current password is incorrect.',
        'account_disabled' => 'Your account has been disabled.',
        'email_not_verified' => 'Your email has not been verified.',
    ],

    // Database Errors
    'database' => [
        'connection' => 'Failed to connect to database.',
        'query' => 'An error occurred while accessing the database.',
        'duplicate' => 'Data already exists in the database.',
        'foreign_key' => 'Cannot delete data because it is still related to other data.',
    ],

    // File Upload Errors
    'upload' => [
        'failed' => 'Failed to upload file.',
        'too_large' => 'File size is too large.',
        'invalid_type' => 'File type is not supported.',
        'not_found' => 'File not found.',
        'permission_denied' => 'No permission to upload file.',
    ],

    // Environment Editor Errors
    'env_editor' => [
        'update_failed' => 'Failed to update environment variable.',
        'key_required' => 'Key is required.',
        'key_not_found' => 'Key not found.',
        'permission_denied' => 'No permission to modify .env file.',
        'file_not_writable' => '.env file is not writable. Check file permissions.',
        'invalid_format' => 'Invalid environment variable format.',
        'update_success' => 'Environment variable updated successfully.',
        'delete_success' => 'Environment variable deleted successfully.',
        'add_success' => 'Environment variable added successfully.',
    ],

    // Permission Errors
    'permission' => [
        'denied' => 'You do not have permission to perform this action.',
        'insufficient' => 'Your permissions are insufficient.',
        'role_required' => ':role role is required to access this page.',
    ],

    // CRUD Operations
    'crud' => [
        'create_failed' => 'Failed to create :resource.',
        'update_failed' => 'Failed to update :resource.',
        'delete_failed' => 'Failed to delete :resource.',
        'retrieve_failed' => 'Failed to retrieve :resource data.',
        'create_success' => ':resource created successfully.',
        'update_success' => ':resource updated successfully.',
        'delete_success' => ':resource deleted successfully.',
    ],

    // Cache Errors
    'cache' => [
        'clear_failed' => 'Failed to clear cache.',
        'clear_success' => 'Cache cleared successfully.',
        'invalid_path' => 'Invalid cache path.',
        'permission_denied' => 'No permission to write cache.',
    ],

    // Storage Errors
    'storage' => [
        'permission_denied' => 'No permission to write to storage.',
        'path_not_found' => 'Storage path not found.',
        'disk_full' => 'Storage is full.',
        'write_failed' => 'Failed to write file to storage.',
    ],

    // Network Errors
    'network' => [
        'timeout' => 'Connection timeout. Please try again.',
        'connection_failed' => 'Failed to connect to server.',
        'no_internet' => 'No internet connection.',
    ],

    // Form Errors
    'form' => [
        'invalid_data' => 'The submitted data is invalid.',
        'missing_fields' => 'Some required fields are missing.',
        'csrf_mismatch' => 'Security token is invalid. Please refresh the page.',
    ],

    // API Errors
    'api' => [
        'invalid_token' => 'Invalid API token.',
        'rate_limit' => 'Too many requests. Please wait a moment.',
        'endpoint_not_found' => 'API endpoint not found.',
        'method_not_allowed' => 'HTTP method not allowed.',
    ],

    // Import/Export Errors
    'import' => [
        'failed' => 'Failed to import data.',
        'invalid_format' => 'Invalid file format.',
        'empty_file' => 'File is empty.',
        'processing_error' => 'An error occurred while processing the file.',
    ],

    'export' => [
        'failed' => 'Failed to export data.',
        'no_data' => 'No data to export.',
        'generation_error' => 'An error occurred while generating the file.',
    ],
];
