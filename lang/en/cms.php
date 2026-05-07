<?php

return [
    'nav' => [
        'group' => [
            'content' => 'Content',
        ],
    ],

    'pages' => [
        'nav' => [
            'label' => 'Pages',
        ],
        'model' => [
            'singular' => 'Page',
            'plural'   => 'Pages',
        ],
        'section' => [
            'details'            => 'Page Details',
            'blocks'             => 'Content Blocks',
            'blocks_description' => 'Drag to reorder. Toggle visibility per block. Each block renders the matching Blade partial; the data fields here override the partial defaults.',
        ],
        'field' => [
            'title'        => 'Title',
            'slug'         => 'Slug',
            'layout'       => 'Layout',
            'status'       => 'Status',
            'published_at' => 'Published at',
            'is_homepage'  => 'Homepage',
            'order_column' => 'Order',
            'blocks_count' => 'Blocks',
            'updated_at'   => 'Last updated',
        ],
        'help' => [
            'slug'        => 'URL identifier. Lowercase letters, numbers, and dashes only.',
            'is_homepage' => 'Marks this page as the site root (/). Only one page should be the homepage.',
        ],
        'status' => [
            'draft'     => 'Draft',
            'published' => 'Published',
        ],
        'layout' => [
            'custom'   => 'Custom',
            'home'     => 'Home',
            'about'    => 'About',
            'products' => 'Products',
            'animal'   => 'Animal',
            'services' => 'Services',
            'contact'  => 'Contact',
            'faq'      => 'FAQ',
        ],
        'block' => [
            'type'       => 'Block type',
            'is_visible' => 'Visible on page',
            'data'       => 'Content fields',
            'data_key'   => 'Field',
            'data_value' => 'Value',
            'add'        => 'Add block',
        ],
    ],
];
