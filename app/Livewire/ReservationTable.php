<?php

namespace App\Livewire;

use App\Models\Reservation;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use App\Enum\ReservationStatus;
use Illuminate\Support\Facades\Blade;

final class ReservationTable extends PowerGridComponent
{
    public string $tableName = 'reservation-table-abkt5r-table';

    public string $sortField = 'id';
    public string $sortDirection = 'desc';

    public bool $deferLoading = true;

    public string $loadingComponent = 'components.loading-spinner-table';

    public bool $showFilters = false;



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
        return Reservation::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        $options = collect(ReservationStatus::values());
        return PowerGrid::fields()
            ->add('name_client')
            ->add(
                'email',
                fn($row) =>
                $row->email
                    ? '<a href="mailto:' . e($row->email) . '" class="text-primary underline">' . e($row->email) . '</a>'
                    : '-'
            )
            ->add(
                'phone',
                fn($row) =>
                $row->phone
                    ? '<a href="tel:' . e($row->phone) . '" class="text-primary underline">' . e($row->phone) . '</a>'
                    : '-'
            )
            ->add('room_id', fn($reservation) => e($reservation->room->name))
            ->add('date_checkin_formatted', fn(Reservation $model) => Carbon::parse($model->date_checkin)->format('d/m/Y'))
            ->add('date_checkout_formatted', fn(Reservation $model) => Carbon::parse($model->date_checkout)->format('d/m/Y'))
            ->add('messages')
            ->add('status', function ($reservation) use ($options) {
                return Blade::render('<x-select-status type="occurrence" :options=$options  :reservationId=$reservationId  :selected=$selected/>', ['options' => $options, 'reservationId' => intval($reservation->id), 'selected' => $reservation->status]);
            });
    }

    public function columns(): array
    {
        return [
            Column::action('Action'),
            Column::make('Room', 'room_id')
                ->fixedOnResponsive(),
            Column::make('Status', 'status')
                ->fixedOnResponsive(),
            Column::make('Name client', 'name_client')
                ->fixedOnResponsive()
                ->sortable()
                ->searchable(),
            Column::make('Email', 'email')
                ->sortable()
                ->searchable(),
            Column::make('Phone', 'phone')
                ->searchable(),
            Column::make('Date check-in', 'date_checkin_formatted', 'date_checkin')
                ->fixedOnResponsive()
                ->searchable()
                ->sortable(),
            Column::make('Date check-out', 'date_checkout_formatted', 'date_checkout')
                ->fixedOnResponsive()
                ->searchable()
                ->sortable(),
            Column::make('Messages', 'messages')
                ->sortable()
                ->searchable()
        ];
    }

    public function filters(): array
    {
        return [
            Filter::datepicker('date_checkin')
                ->params([
                    'dateFormat' => 'd-m-Y',
                    'enableTime' => false,
                ])
                ->builder(function ($builder, array $value) {
                    $dateCheckin = Carbon::parse($value['start']) ?? '';
                    $dateCheckout = Carbon::parse($value['end']) ?? '';
                    $builder
                        ->whereDate('date_checkin', '<=', $dateCheckout)
                        ->whereDate('date_checkout', '>=', $dateCheckin);
                }),
            Filter::enumSelect('status')
                ->dataSource(ReservationStatus::cases())
                ->optionLabel('reservations.status'),
        ];
    }

    #[\Livewire\Attributes\On('deleteReservation')]
    public function delete($rowId): void
    {
        Reservation::safeDelete($rowId);
        $this->dispatch('pg:eventRefresh-reservation-table-abkt5r-table');
    }

    #[\Livewire\Attributes\On('bulkDelete.reservation-table-abkt5r-table')]
    public function bulkDelete(): void
    {
        if ($this->checkboxValues) {
            Reservation::safeBulkDelete($this->checkboxValues);
            $this->js('window.pgBulkActions.clearAll()'); // clear the count on the interface.
            $this->dispatch('pg:eventRefresh-reservation-table-abkt5r-table');
        }
    }

    public function changeStatus(string $newStatus, int $reservationId): void
    {
        Reservation::where('id', $reservationId)
            ->update(['status' => $newStatus]);
    }


    public function actions(Reservation $row): array
    {
        return [
            Button::add('edit')
                ->slot('✏️')
                ->id()
                ->class('text-blue-600 hover:text-blue-800 text-lg cursor-pointer')
                ->route('admin.reservations.edit', ['reservation' => $row->id]),

            Button::add('delete')
                ->slot('🗑️')
                ->id()
                ->class('text-red-600 hover:text-red-800 text-lg cursor-pointer')
                ->confirm('Are you sure you want to delete?')
                ->dispatch('deleteReservation', ['rowId' => $row->id]),
        ];
    }
}
