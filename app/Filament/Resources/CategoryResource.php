<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
// use Closure;
// use PhpParser\Node\Expr\Closure;
use Mockery\Matcher\Closure;
use Illuminate\Support\Str;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        // return $form
        //     ->schema([
        //         Card::make()->schema([
        //             TextInput::make('name')->reactive()
        //             ->afterStateUpdated(function (Closure $set, $state) {
        //                 $set('slug', Str::slug($state));
        //             })->required(),
        //             TextInput::make('slug')->required()
        //         ])
        //     ]);

        return $form
        ->schema([
            Card::make()->schema([
                TextInput::make('name')->reactive()
                    ->afterStateUpdated(function ($state) use ($form) {
                        $form->set('slug', Str::slug($state));
                    })->required(),
                TextInput::make('slug')->required()
            ])
        ]);
        
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }    
}
