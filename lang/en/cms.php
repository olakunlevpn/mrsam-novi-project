<?php

return [
    'nav' => [
        'group' => [
            'content' => 'Content',
            'catalog' => 'Catalog',
            'system'  => 'System',
            'inbox'   => 'Inbox',
        ],
    ],

    'block_fields' => [
        'hero' => [
            'subtitle'    => 'Subtitle / eyebrow',
            'headline'    => 'Headline',
            'video_src'   => 'Background video URL',
            'cta_label'   => 'Button label',
            'cta_url'     => 'Button URL',
        ],
        'page_header' => [
            'title'            => 'Heading',
            'background_image' => 'Background image URL',
        ],
        'breadcrumb' => [
            'label' => 'Current page label',
        ],
        'cta_booking' => [
            'tagline'          => 'Tagline',
            'title'            => 'Heading (HTML allowed for line breaks)',
            'title_help'       => 'Use <br> for line breaks.',
            'submit_label'     => 'Submit button label',
            'form_action'      => 'Form action URL',
            'background_image' => 'Background image URL',
            'image_vet'        => 'Side image URL',
        ],
        'partners_carousel' => [
            'title'      => 'Section title (HTML allowed)',
            'title_help' => 'Use <br> for line breaks.',
            'partners'   => 'Partner logos',
            'logo'       => 'Logo image URL',
            'url'        => 'Click-through URL',
            'alt'        => 'Alt text',
            'add'        => 'Add partner',
        ],
        'testimonials' => [
            'tagline'     => 'Tagline',
            'title'       => 'Heading',
            'items'       => 'Testimonials',
            'name'        => 'Name',
            'designation' => 'Designation',
            'image'       => 'Photo URL',
            'rating'      => 'Star rating',
            'content'     => 'Quote',
            'add'         => 'Add testimonial',
        ],
        'contact_form' => [
            'action_url'      => 'Submit URL',
            'action_url_help' => 'Default: local controller. Override to point at any external endpoint (e.g. formsubmit.co).',
            'subject'         => 'Default subject',
            'submit_label'    => 'Submit button label',
            'success_message' => 'Success message',
        ],
        'contact_map' => [
            'embed_url'      => 'Google Maps embed URL',
            'embed_url_help' => 'Paste from Google Maps > Share > Embed a map > Copy HTML > extract the src attribute.',
        ],
        'faq_accordion' => [
            'eyebrow'  => 'Eyebrow text',
            'title'    => 'Heading',
            'subtitle' => 'Subtitle',
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
            'seo'                => 'SEO & Social',
            'seo_description'    => 'Search engine and social-share metadata for this page.',
            'blocks'             => 'Content Blocks',
            'blocks_description' => 'Drag to reorder. Toggle visibility per block. Each block renders the matching Blade partial; the data fields here override the partial defaults.',
        ],
        'seo' => [
            'title'            => 'Meta title',
            'title_help'       => 'Overrides the default <title> tag. 50-60 characters works best.',
            'meta_description' => 'Meta description',
            'og_title'         => 'Open Graph title',
            'og_description'   => 'Open Graph description',
            'og_image'         => 'Open Graph image URL',
            'canonical_url'    => 'Canonical URL',
            'noindex'          => 'Hide from search engines',
            'noindex_help'     => 'When on, robots are told not to index this page.',
            'robots'           => 'Robots directives',
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

    'settings' => [
        'nav'   => ['label' => 'Settings (raw)'],
        'model' => ['singular' => 'Setting', 'plural' => 'Settings'],
        'field' => [
            'group'      => 'Group',
            'key'        => 'Key',
            'value'      => 'Value',
            'updated_at' => 'Last updated',
        ],
        'help' => [
            'key'   => 'Dot-notation identifier (e.g. brand.tagline, contact.email).',
            'value' => 'Plain text value. Stored as JSON; supports strings, numbers, and booleans.',
        ],
    ],

    'site_settings' => [
        'nav'   => ['label' => 'Site Settings'],
        'title' => 'Site Settings',
        'save'  => 'Save changes',
        'saved' => 'Settings saved.',
        'group' => [
            'brand'   => 'Brand',
            'contact' => 'Contact',
            'social'  => 'Social',
            'site'    => 'Site',
        ],
        'field' => [
            'brand_name'         => 'Brand name',
            'brand_tagline'      => 'Tagline',
            'brand_logo'         => 'Logo URL',
            'contact_email'      => 'Email address',
            'contact_phone'      => 'Phone number',
            'contact_address'    => 'Address',
            'social_facebook'    => 'Facebook URL',
            'social_instagram'   => 'Instagram URL',
            'site_title_suffix'  => 'Title suffix',
        ],
    ],

    'menus' => [
        'nav'   => ['label' => 'Menus'],
        'model' => ['singular' => 'Menu', 'plural' => 'Menus'],
        'section' => [
            'details' => 'Menu Details',
            'items'   => 'Menu Items',
        ],
        'field' => [
            'name'         => 'Name',
            'location'     => 'Location',
            'items_count'  => 'Items',
            'updated_at'   => 'Last updated',
        ],
        'item' => [
            'label'        => 'Label',
            'route_name'   => 'Route name',
            'url'          => 'External URL',
            'target'       => 'Target',
            'order_column' => 'Order',
            'children'     => 'Sub-items',
            'add'          => 'Add menu item',
            'add_child'    => 'Add sub-item',
        ],
        'target' => [
            'self'  => 'Same window',
            'blank' => 'New window',
        ],
    ],

    'faqs' => [
        'nav'   => ['label' => 'FAQs'],
        'model' => ['singular' => 'FAQ', 'plural' => 'FAQs'],
        'field' => [
            'category'     => 'Category',
            'question'     => 'Question',
            'answer'       => 'Answer',
            'order_column' => 'Order',
            'is_published' => 'Published',
            'updated_at'   => 'Last updated',
        ],
    ],

    'faq_categories' => [
        'nav'   => ['label' => 'FAQ Categories'],
        'model' => ['singular' => 'FAQ Category', 'plural' => 'FAQ Categories'],
        'field' => [
            'name'         => 'Name',
            'slug'         => 'Slug',
            'order_column' => 'Order',
            'faqs_count'   => 'Questions',
        ],
    ],

    'contact_submissions' => [
        'nav'   => ['label' => 'Contact Submissions'],
        'model' => ['singular' => 'Submission', 'plural' => 'Contact Submissions'],
        'field' => [
            'name'       => 'Name',
            'email'      => 'Email',
            'phone'      => 'Phone',
            'subject'    => 'Subject',
            'message'    => 'Message',
            'ip'         => 'IP address',
            'user_agent' => 'User agent',
            'status'     => 'Status',
            'created_at' => 'Received at',
        ],
        'status' => [
            'new'      => 'New',
            'read'     => 'Read',
            'archived' => 'Archived',
        ],
    ],

    'animals' => [
        'nav'   => ['label' => 'Animals'],
        'model' => ['singular' => 'Animal', 'plural' => 'Animals'],
        'field' => [
            'name'         => 'Name',
            'slug'         => 'Slug',
            'description'  => 'Description',
            'hero_image'   => 'Hero image',
            'order_column' => 'Order',
            'categories_count' => 'Categories',
            'products_count'   => 'Products',
        ],
    ],

    'product_categories' => [
        'nav'   => ['label' => 'Product Categories'],
        'model' => ['singular' => 'Category', 'plural' => 'Product Categories'],
        'field' => [
            'animal'         => 'Animal',
            'name'           => 'Name',
            'slug'           => 'Slug',
            'description'    => 'Description',
            'order_column'   => 'Order',
            'products_count' => 'Products',
        ],
    ],

    'products' => [
        'nav'   => ['label' => 'Products'],
        'model' => ['singular' => 'Product', 'plural' => 'Products'],
        'section' => [
            'identity' => 'Identity',
            'taxonomy' => 'Taxonomy',
            'content'  => 'Content',
            'media'    => 'Media',
        ],
        'field' => [
            'animal'             => 'Animal',
            'product_category'   => 'Category',
            'name'               => 'Name',
            'slug'               => 'Slug',
            'sku'                => 'SKU',
            'hero_image'         => 'Hero image',
            'description'        => 'Description',
            'composition'        => 'Composition',
            'benefits'           => 'Benefits',
            'usage_instructions' => 'Usage instructions',
            'status'             => 'Status',
            'order_column'       => 'Order',
        ],
        'status' => [
            'draft'     => 'Draft',
            'published' => 'Published',
        ],
    ],
];
