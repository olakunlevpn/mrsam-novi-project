<?php

namespace App\Http\Controllers;

use App\Models\Setting;

class RobotsController extends Controller
{
    /**
     * Serve /robots.txt. Reads the admin-editable `seo.robots_txt` setting;
     * falls back to a sane default (block admin/auth surfaces, advertise the
     * sitemap). Note: the legacy `public/robots.txt` file must be removed so
     * Laravel's routing reaches this controller -- Laravel never serves a
     * controller response when a matching static file already exists.
     */
    public function index()
    {
        $override = Setting::get('seo.robots_txt');
        $body = is_string($override) && trim($override) !== ''
            ? $override
            : $this->defaultRobots();

        return response($body, 200, ['Content-Type' => 'text/plain']);
    }

    private function defaultRobots(): string
    {
        $sitemapUrl = route('sitemap');

        return <<<TXT
        User-agent: *
        Disallow: /admin
        Disallow: /login
        Disallow: /register
        Sitemap: {$sitemapUrl}
        TXT;
    }
}
