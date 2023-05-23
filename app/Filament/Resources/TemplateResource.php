<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Email;
use App\Models\Template;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\TemplateResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TemplateResource\RelationManagers;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

class TemplateResource extends Resource
{
    protected static ?string $model = Template::class;

    protected static ?string $navigationIcon = 'heroicon-o-template';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('subscriber_per_request')
                    ->integer()
                    ->hint('Leave blank for all subscribers.')
                    ->hintColor('danger'),
                Forms\Components\TextInput::make('request_interval')
                    ->integer()
                    ->required()
                    ->default(5)
                    ->hint('Time taken between each email requests in mins.')
                    ->hintColor('danger'),
                Forms\Components\Select::make('status')
                    ->options([
                        'not_scheduled' => 'Not scheduled',
                        'scheduled' => 'Scheduled',
                        // 'sent' => 'Sent',
                    ])
                    ->required()
                    ->hint('Set status to "Not scheduled" to disable sending emails on save.')
                    ->hintColor('danger'),
                Forms\Components\TextInput::make('subject')
                    ->required()
                    ->maxLength(191)
                    ->columnSpanFull(),
                TinyEditor::make('body')
                    ->minHeight(300)
                    ->required()
                    ->helperText('Shortcodes: [username], [userEmail],  [button color="success" url="https://example.com" text="Some Text"], button supported colors are: success, error, primary.')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('subscriber_per_request')
                    ->default('All subscribers')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('request_interval')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('subject')
                    ->searchable()
                    ->sortable()
                    ->limit(20),
                Tables\Columns\TextColumn::make('body')
                    ->html()
                    ->limit(20)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\BadgeColumn::make('status')->enum([
                    'not_scheduled' => 'Not scheduled',
                    'scheduled' => 'Scheduled',
                    'sent' => 'Sent',
                ])->colors([
                    'warning' => 'scheduled',
                    'success' => 'sent',
                    'danger' => 'not_scheduled',
                ]),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    // ->default('Not Deleted'),
                Tables\Columns\TextColumn::make('created_at')
                    ->since()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('send')
                    ->label('')
                    ->icon('heroicon-s-inbox-in')
                    ->color('warning')
                    ->tooltip('Send Email to subscribers.')
                    ->action(function(Template $record){
                        $record->update([
                            'status' => 'scheduled'
                        ]);
                    })
                    ->visible(function(Template $record): bool {
                        if($record->status == 'not_scheduled'){
                            return 1;
                        }else{
                            return 0;
                        }
                    }),
                Tables\Actions\Action::make('cancel')
                    ->label('')
                    ->icon('heroicon-s-x-circle')
                    ->color('danger')
                    ->tooltip('Cancel sending Email to subscribers.')
                    ->action(function(Template $record){
                        $record->update([
                            'status' => 'not_scheduled'
                        ]);
                        Email::where('template_id', $record->id)->update([
                            'status' => 0,
                        ]);
                    })
                    ->visible(function(Template $record): bool {
                        if($record->status == 'scheduled'){
                            return 1;
                        }else{
                            return 0;
                        }
                    }),
                Tables\Actions\EditAction::make()
                    ->label('')
                    ->tooltip('Edit')
                    ->modalWidth('md'),
                Tables\Actions\ViewAction::make()
                    ->label('')
                    ->tooltip('View'),
                Tables\Actions\DeleteAction::make()
                    ->label('')
                    ->tooltip('Delete'),
                Tables\Actions\ForceDeleteAction::make()
                    ->label('')
                    ->tooltip('Force Delete'),
                Tables\Actions\RestoreAction::make()
                    ->label('')
                    ->tooltip('Restore')
                    ->color('success'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTemplates::route('/'),
            'create' => Pages\CreateTemplate::route('/create'),
            'edit' => Pages\EditTemplate::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
