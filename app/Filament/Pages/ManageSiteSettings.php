<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use App\View\Composers\SiteComposer;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ManageSiteSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.pages.manage-site-settings';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAdjustmentsHorizontal;

    protected static ?int $navigationSort = 91;

    /**
     * Form data state. Each key is `group__key` (double-underscore) so the
     * Filament form can use a flat schema while the underlying storage stays
     * grouped in the settings table.
     *
     * @var array<string, mixed>
     */
    public array $data = [];

    public static function getNavigationGroup(): ?string
    {
        return __('cms.nav.group.system');
    }

    public static function getNavigationLabel(): string
    {
        return __('cms.site_settings.nav.label');
    }

    public function getTitle(): string
    {
        return __('cms.site_settings.title');
    }

    public function mount(): void
    {
        $values = [];
        foreach (self::definitions() as $group => $fields) {
            foreach ($fields as $key => $meta) {
                $values[self::flatKey($group, $key)] = Setting::get("{$group}.{$key}");
            }
        }
        $this->form->fill($values);
    }

    public function form(Schema $schema): Schema
    {
        $sections = [];
        foreach (self::definitions() as $group => $fields) {
            $components = [];
            foreach ($fields as $key => $meta) {
                $field = ($meta['textarea'] ?? false)
                    ? Textarea::make(self::flatKey($group, $key))->rows(3)->autosize()
                    : TextInput::make(self::flatKey($group, $key))->maxLength(500);

                $field = $field
                    ->label(__($meta['label']))
                    ->placeholder($meta['placeholder'] ?? '')
                    ->columnSpanFull();

                if ($meta['required'] ?? false) {
                    $field = $field->required();
                }
                if ($meta['url'] ?? false) {
                    $field = $field->url();
                }
                if ($meta['email'] ?? false) {
                    $field = $field->email();
                }

                $components[] = $field;
            }

            $sections[] = Section::make(__("cms.site_settings.group.{$group}"))
                ->columns(1)
                ->components($components);
        }

        return $schema
            ->statePath('data')
            ->components($sections);
    }

    public function save(): void
    {
        $values = $this->form->getState();

        foreach (self::definitions() as $group => $fields) {
            foreach ($fields as $key => $meta) {
                $value = $values[self::flatKey($group, $key)] ?? null;
                Setting::set("{$group}.{$key}", $value, $group);
            }
        }

        SiteComposer::clearCache();

        Notification::make()
            ->title(__('cms.site_settings.saved'))
            ->success()
            ->send();
    }

    /**
     * @return array<int, Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label(__('cms.site_settings.save'))
                ->action('save')
                ->keyBindings(['mod+s'])
                ->color('primary'),
        ];
    }

    /**
     * Map a (group, key) pair to a flat form-state key.
     */
    private static function flatKey(string $group, string $key): string
    {
        return $group . '__' . str_replace('.', '_', $key);
    }

    /**
     * The settings UI is driven by this declarative map. Each leaf is a
     * setting key (relative to its group) and a metadata array.
     *
     * @return array<string, array<string, array<string, mixed>>>
     */
    private static function definitions(): array
    {
        return [
            'brand' => [
                'name'    => ['label' => 'cms.site_settings.field.brand_name'],
                'tagline' => ['label' => 'cms.site_settings.field.brand_tagline'],
                'logo'    => ['label' => 'cms.site_settings.field.brand_logo', 'placeholder' => '/assets/images/logo.png'],
            ],
            'contact' => [
                'email'   => ['label' => 'cms.site_settings.field.contact_email',   'email' => true],
                'phone'   => ['label' => 'cms.site_settings.field.contact_phone'],
                'address' => ['label' => 'cms.site_settings.field.contact_address', 'textarea' => true],
            ],
            'social' => [
                'facebook'  => ['label' => 'cms.site_settings.field.social_facebook',  'url' => true],
                'instagram' => ['label' => 'cms.site_settings.field.social_instagram', 'url' => true],
            ],
            'site' => [
                'title_suffix' => ['label' => 'cms.site_settings.field.site_title_suffix'],
            ],
            'seo' => [
                'robots_txt' => [
                    'label'       => 'cms.site_settings.field.seo_robots_txt',
                    'placeholder' => "User-agent: *\nDisallow: /admin",
                    'textarea'    => true,
                ],
            ],
        ];
    }
}
