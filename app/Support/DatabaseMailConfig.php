<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Facades\Config;

/**
 * Overrides the runtime mail configuration from the admin-managed
 * `mail.*` settings (Settings > Mail / SMTP). When no host is configured
 * the application falls back to whatever the .env / config files provide,
 * so this is a no-op on a fresh install.
 */
class DatabaseMailConfig
{
    /**
     * Apply DB-stored SMTP settings to the live mail config. Safe to call on
     * every request; silently no-ops if the settings table is unavailable
     * (e.g. before the first migration) or the host is blank.
     */
    public static function apply(): void
    {
        try {
            $host = Setting::get('mail.host');
        } catch (\Throwable) {
            return; // DB not ready (install / migrate) — leave config untouched.
        }

        if (blank($host)) {
            return;
        }

        $encryption = Setting::get('mail.encryption', 'tls');
        // Laravel 11+ SMTP transport uses a scheme: 'smtps' = implicit TLS
        // (port 465); null = STARTTLS / plain (port 587/25).
        $scheme = $encryption === 'ssl' ? 'smtps' : null;

        Config::set('mail.default', 'smtp');
        Config::set('mail.mailers.smtp.host', $host);
        Config::set('mail.mailers.smtp.port', (int) (Setting::get('mail.port', 587) ?: 587));
        Config::set('mail.mailers.smtp.username', Setting::get('mail.username'));
        Config::set('mail.mailers.smtp.password', Setting::get('mail.password'));
        Config::set('mail.mailers.smtp.scheme', $scheme);

        if (filled($from = Setting::get('mail.from_address'))) {
            Config::set('mail.from.address', $from);
        }
        if (filled($fromName = Setting::get('mail.from_name'))) {
            Config::set('mail.from.name', $fromName);
        }
    }
}
