<?php

namespace App\Livewire;

use App\Models\Room;
use Illuminate\Support\Str;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\Blade;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class RoomTable extends PowerGridComponent
{
    public string $tableName = 'room-table-wj2ae9-table';

    public bool $deferLoading = true;

    public string $loadingComponent = 'components.loading-spinner-table';


    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::header()
                ->showToggleColumns()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
            PowerGrid::responsive()
        ];
    }

    public function header(): array
    {
        return [
            Button::add('bulk-delete')
                ->slot('Bulk delete (<span x-text="window.pgBulkActions.count(\'' . $this->tableName . '\')"></span>)')
                ->class('bg-red-500 text-white uppercase rounded-md py-2 px-3 sm:text-sm sm:leading-6 space-x-5 cursor-pointer')
                ->dispatch('bulkDelete.' . $this->tableName, [])
                ->attributes([
                    'x-show' => "window.pgBulkActions.count('{$this->tableName}') > 0",
                    'x-cloak' => true
                ])
        ];
    }

    public function datasource(): Builder
    {
        return Room::query()->orderByRaw("CAST(SUBSTRING_INDEX(name, ' ', -1) AS UNSIGNED)");
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('name')
            ->add('description', fn($row) => '<span title="' . $row->description . '">' . Str::limit($row->description, 50) . '</span>')
            ->add('price')
            ->add('price_formatted', function ($row) {
                return Number::currency($row->price, in: 'EUR', locale: 'it_IT');
            })
            ->add('beds')
            ->add('meters')
            ->add('preview', fn($room) => Blade::render(
                '<x-image.room-gallery :images="$images" :alt="$alt" />',
                [
                    'images' => $room->allImages(), // include preview + immagini gallery
                    'alt' => $room->name,
                ]
            ))
            ->add('actived');
    }

    public function columns(): array
    {
        return [
            Column::action('Action'),
            Column::make('Gallery', 'preview'),
            Column::make('Name', 'name')
                ->fixedOnResponsive()
                ->sortable()
                ->searchable(),

            Column::make('Description', 'description')
                ->sortable()
                ->fixedOnResponsive()
                ->searchable(),

            Column::make('Price', 'price_formatted', 'price')
                ->sortable(fn($builder, $dir) => $builder->orderBy('price', $dir))
                ->searchable(),

            Column::make('Beds', 'beds')
                ->sortable()
                ->searchable(),

            Column::make('Meters', 'meters')
                ->sortable()
                ->searchable(),

            Column::make('Actived', 'actived')
                ->sortable()
                ->searchable()
                ->toggleable(
                    hasPermission: true,
                    trueLabel: 'Active',
                    falseLabel: 'Disabled'
                )
        ];
    }

    public function filters(): array
    {
        return [
            Filter::boolean('actived', 'actived')
        ];
    }

    #[\Livewire\Attributes\On('deleteRoom')]
    public function delete($rowId): void
    {
        Room::safeDelete($rowId);
        $this->dispatch('pg:eventRefresh-room-table-wj2ae9-table');
    }

    #[\Livewire\Attributes\On('bulkDelete.room-table-wj2ae9-table')]
    public function bulkDelete(): void
    {
        if ($this->checkboxValues) {
            Room::safeBulkDelete($this->checkboxValues);
            $this->js('window.pgBulkActions.clearAll()'); // clear the count on the interface.
            $this->dispatch('pg:eventRefresh-room-table-wj2ae9-table');
        }
    }

    public function actions(Room $row): array
    {
        return [
            Button::add('edit')
                ->slot('✏️')
                ->id()
                ->class('text-blue-600 hover:text-blue-800 text-lg cursor-pointer')
                ->route('admin.rooms.edit', ['room' => $row->id]),

            Button::add('delete')
                ->slot('🗑️')
                ->id()
                ->class('text-red-600 hover:text-red-800 text-lg cursor-pointer')
                ->confirm('Are you sure you want to delete?')
                ->dispatch('deleteRoom', ['rowId' => $row->id]),
        ];
    }

    public function onUpdatedToggleable(string|int $id, string $field, string $value): void
    {
        Room::query()->find($id)->update([
            $field => e($value),
        ]);
    }
}
