<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings\SettingsCluster;
use App\Models\Setting;
use App\View\Composers\SiteComposer;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
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

/**
 * Shared base for every page inside the Settings cluster.
 *
 * Each concrete page declares which setting keys it edits via
 * fieldDefinitions(); this base handles fill/save and turns the
 * declarative meta into Filament components with helper text.
 */
abstract class AbstractSettingsPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $cluster = SettingsCluster::class;

    protected string $view = 'filament.clusters.settings.pages.abstract-settings-page';

    /**
     * Flat form-state keyed by `group__key`.
     *
     * @var array<string, mixed>
     */
    public array $data = [];

    /**
     * Setting fields this page edits.
     *
     * @return array<string, array<string, array<string, mixed>>> [group => [key => meta]]
     */
    abstract protected function fieldDefinitions(): array;

    public static function canAccess(): bool
    {
        return auth()->user()?->is_admin === true;
    }

    public function mount(): void
    {
        $values = [];
        foreach ($this->fieldDefinitions() as $group => $fields) {
            foreach ($fields as $key => $meta) {
                $values[$this->flatKey($group, $key)] = Setting::get("{$group}.{$key}");
            }
        }
        $this->form->fill($values);
    }

    public function form(Schema $schema): Schema
    {
        $sections = [];
        foreach ($this->fieldDefinitions() as $group => $fields) {
            $components = [];
            foreach ($fields as $key => $meta) {
                $components[] = $this->buildField($group, $key, $meta);
            }
            $sections[] = Section::make(__("cms.settings_cluster.group.{$group}"))
                ->description(__("cms.settings_cluster.group_description.{$group}"))
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

        foreach ($this->fieldDefinitions() as $group => $fields) {
            foreach ($fields as $key => $meta) {
                $value = $values[$this->flatKey($group, $key)] ?? null;
                Setting::set("{$group}.{$key}", $value, $group);
            }
        }

        SiteComposer::clearCache();

        Notification::make()
            ->title(__('cms.settings_cluster.saved'))
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
                ->label(__('cms.settings_cluster.save'))
                ->action('save')
                ->icon(Heroicon::OutlinedCheck)
                ->keyBindings(['mod+s'])
                ->color('primary'),
        ];
    }

    protected function flatKey(string $group, string $key): string
    {
        return $group . '__' . str_replace('.', '_', $key);
    }

    /**
     * @param array<string, mixed> $meta
     */
    protected function buildField(string $group, string $key, array $meta): mixed
    {
        $name        = $this->flatKey($group, $key);
        $type        = $meta['type'] ?? 'text';
        $helperKey   = "cms.settings_cluster.helper.{$group}_{$key}";
        $helperText  = trans()->has($helperKey) ? __($helperKey) : null;

        if ($type === 'gallery_repeater') {
            return Repeater::make($name)
                ->label(__($meta['label']))
                ->helperText($helperText)
                ->components([
                    FileUpload::make('src')
                        ->label(__('cms.settings_cluster.field.footer_gallery_src'))
                        ->required()
                        ->image()
                        ->disk('public')
                        ->directory('footer/gallery')
                        ->imageEditor()
                        ->maxSize(4096)
                        ->helperText(__('cms.settings_cluster.helper.footer_gallery_src')),
                    TextInput::make('alt')
                        ->label(__('cms.settings_cluster.field.footer_gallery_alt'))
                        ->maxLength(191)
                        ->placeholder(__('cms.settings_cluster.field.footer_gallery_alt'))
                        ->helperText(__('cms.settings_cluster.helper.footer_gallery_alt')),
                ])
                ->columns(2)
                ->reorderable()
                ->collapsible()
                ->collapsed()
                ->minItems(0)
                ->maxItems(12)
                ->defaultItems(0)
                ->addActionLabel(__('cms.settings_cluster.field.footer_gallery_add'))
                ->itemLabel(fn (array $state): ?string => $state['alt'] ?? $state['src'] ?? null)
                ->columnSpanFull();
        }

        if ($type === 'image_upload') {
            $upload = FileUpload::make($name)
                ->label(__($meta['label']))
                ->image()
                ->disk('public')
                ->directory($meta['directory'] ?? 'branding')
                ->imageEditor()
                ->maxSize($meta['max_size'] ?? 2048)
                ->helperText($helperText)
                ->columnSpanFull();
            if (! empty($meta['accept'])) {
                $upload = $upload->acceptedFileTypes($meta['accept']);
            }
            if ($meta['required'] ?? false) {
                $upload = $upload->required();
            }
            return $upload;
        }

        if ($type === 'favicon_upload') {
            return FileUpload::make($name)
                ->label(__($meta['label']))
                ->disk('public')
                ->directory($meta['directory'] ?? 'branding/favicons')
                ->acceptedFileTypes(['image/x-icon', 'image/vnd.microsoft.icon', 'image/png', 'image/svg+xml'])
                ->maxSize(512)
                ->helperText($helperText)
                ->columnSpanFull();
        }

        $field = ($meta['textarea'] ?? false)
            ? Textarea::make($name)->rows(4)->autosize()
            : TextInput::make($name)->maxLength(500);

        $field = $field
            ->label(__($meta['label']))
            ->placeholder($meta['placeholder'] ?? '')
            ->helperText($helperText)
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
}
