<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Title
    |--------------------------------------------------------------------------
    |
    | Displayed when no H1 or frontmatter title is found.
    |
    */
    'title' => env('DOCS_TITLE', 'Documentation'),

    /*
    |--------------------------------------------------------------------------
    | Docs Path
    |--------------------------------------------------------------------------
    |
    | Directory where the .md files are located.
    |
    */
    'docs_path' => resource_path('docs'),

    /*
    |--------------------------------------------------------------------------
    | Home Page
    |--------------------------------------------------------------------------
    |
    | Default file displayed when accessing /admin/docs without a slug.
    |
    */
    'home' => 'home.md',

    /*
    |--------------------------------------------------------------------------
    | Cache (minutes)
    |--------------------------------------------------------------------------
    |
    | Cache time for markdown parsing and navigation.
    | Use 0 to disable (ideal during development).
    |
    */
    'cache_minutes' => env('DOCS_CACHE', 10),

    /*
    |--------------------------------------------------------------------------
    | Login Route
    |--------------------------------------------------------------------------
    |
    | Route to redirect unauthorized users.
    | null = returns 403
    |
    */
    'login_route' => null,

];
