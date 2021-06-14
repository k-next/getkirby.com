<?php

use Kirby\Toolkit\Str;

return function () {
    $categories = option('plugins.categories');

    $errors = [];
    $errorMessages = [
        'csrf' => 'CSRF token mismatch!',
        'title' => 'Please enter a valid plugin title',
        'url' => 'Please enter a valid GitHub or GitLab repository url',
        'category' => 'Please select a category',
        'description' => 'Please enter a plugin description that between 20 and 255 characters',
        'developer' => 'Please enter a plugin author name',
        'upload-nofile' => 'The screenshot could not be uploaded',
        'upload-size' => 'Screenshot is larger than 1 MB',
        'upload-type' => 'Screenshot is not a PNG',
        'upload-dimensions' => 'Screenshot max resolution must be 1280x800 px',
        'github-api' => 'The plugin could not be submit',
        'github-allowed' => 'You can only add GitHub repositories. If it is not a GitHub repository, please contact us.',
        'invalid-url' => 'Your plugin URL is invalid. Please check your repository url.',
        'exists' => 'The plugin already exists in plugins directory.',
        'repository-not-found' => 'The repository not found.',
        'no-composer' => 'No valid composer.json file found.',
        'no-kirby-plugin' => 'The composer.json file must be of type "kirby-plugin".',
        'unknown' => 'Unknown error occurred.',
    ];

    if ($status = param('error')) {
        if (Str::contains($status, ',')) {
            foreach (Str::split($status, ',') as $code) {
                $errors[] = $errorMessages[$code] ?? $errorMessages['unknown'];
            }
        } else {
            $errors[] = $errorMessages[$status] ?? $errorMessages['unknown'];
        }
    }

    return [
        'categories' => $categories,
        'currentCategory' => null,
        'errors' => $errors,
    ];
};
