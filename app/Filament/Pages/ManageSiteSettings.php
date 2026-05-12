<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use App\View\Composers\SiteComposer;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Repeater;
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
                $components[] = $this->buildField($group, $key, $meta);
            }

            $sections[] = Section::make(__("cms.site_settings.group.{$group}"))
                ->columns(1)
                ->components($components);
        }

        return $schema
            ->statePath('data')
            ->components($sections);
    }

    /**
     * Build the Filament component for a single (group, key) setting.
     *
     * @param array<string, mixed> $meta
     */
    private function buildField(string $group, string $key, array $meta): mixed
    {
        $name = self::flatKey($group, $key);
        $type = $meta['type'] ?? 'text';

        if ($type === 'gallery_repeater') {
            return Repeater::make($name)
                ->label(__($meta['label']))
                ->components([
                    TextInput::make('src')
                        ->label(__('cms.site_settings.field.footer_gallery_src'))
                        ->required()
                        ->maxLength(500)
                        ->placeholder('/storage/footer/gallery-1.jpg'),
                    TextInput::make('alt')
                        ->label(__('cms.site_settings.field.footer_gallery_alt'))
                        ->maxLength(191)
                        ->placeholder(__('cms.site_settings.field.footer_gallery_alt')),
                ])
                ->columns(2)
                ->reorderable()
                ->collapsible()
                ->collapsed()
                ->minItems(0)
                ->maxItems(12)
                ->defaultItems(0)
                ->addActionLabel(__('cms.site_settings.field.footer_gallery_add'))
                ->itemLabel(fn (array $state): ?string => $state['alt'] ?? $state['src'] ?? null)
                ->columnSpanFull();
        }

        $field = ($meta['textarea'] ?? false)
            ? Textarea::make($name)->rows(3)->autosize()
            : TextInput::make($name)->maxLength(500);

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

        return $field;
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
            'footer' => [
                'categories_title' => ['label' => 'cms.site_settings.field.footer_categories_title'],
                'gallery_title'    => ['label' => 'cms.site_settings.field.footer_gallery_title'],
                'products_title'   => ['label' => 'cms.site_settings.field.footer_products_title'],
                'gallery_images'   => [
                    'label' => 'cms.site_settings.field.footer_gallery_images',
                    'type'  => 'gallery_repeater',
                ],
            ],
        ];
    }
}
