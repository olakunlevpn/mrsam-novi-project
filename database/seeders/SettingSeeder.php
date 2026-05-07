<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['brand.name',        'Novi Agro',                                                   'brand'],
            ['brand.tagline',     'Quality Feed - Healthy Life',                                  'brand'],
            ['brand.logo',        '/assets/images/images-removebg-preview.png',                   'brand'],
            ['contact.email',     'info@novi-agro.com',                                           'contact'],
            ['contact.phone',     '+2347041041756',                                               'contact'],
            ['contact.address',   'New Garage, Ibadan.',                                          'contact'],
            ['social.facebook',   'https://www.facebook.com/profile.php?id=100077163775495',      'social'],
            ['social.instagram',  'https://www.instagram.com/novi_agroltd/',                      'social'],
            ['site.title_suffix', '| Quality Feeds - Healthy Life',                               'site'],
        ];

        foreach ($settings as [$key, $value, $group]) {
            Setting::set($key, $value, $group);
        }
    }
}
