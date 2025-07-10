<?php

namespace App\Livewire;

use App\Models\Room;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Collection;

class RoomGrid extends Component
{
    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $dateFromTo = '';
    public $perPage = 12;
    public $peoples = null;

    public $page = 1;
    public $rooms;

    protected $queryString = ['dateFromTo'];

    public function mount()
    {
        $this->rooms = collect();
        $this->loadRooms();
    }

    public function updatedSearch()
    {
        $this->resetState();
    }

    public function updatedSortField()
    {
        $this->resetState();
    }

    public function updatedSortDirection()
    {
        $this->resetState();
    }

    public function updatedDateFromTo()
    {
        $this->resetState();
    }

    public function updatedPeoples()
    {
        $this->resetState();
    }

    public function loadMore()
    {
        $this->page++;
        $this->loadRooms();
    }

    public function resetState()
    {
        $this->page = 1;
        $this->rooms = collect();
        $this->loadRooms();
    }

    protected function loadRooms()
    {
        [$dateFrom, $dateTo] = $this->parseDateRange();

        $query = Room::query()
            ->where('actived', 1)
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('beds', 'like', '%' . $this->search . '%')
                        ->orWhere('meters', 'like', '%' . $this->search . '%')
                        ->orWhere('price', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->peoples, function ($q) {
                $q->where('peoples', '>=', $this->peoples);
            })
            ->when($dateFrom && $dateTo, function ($q) use ($dateFrom, $dateTo) {
                $q->whereDoesntHave('reservations', function ($sub) use ($dateFrom, $dateTo) {
                    $sub->whereDate('date_checkin', '<=', $dateTo)
                        ->whereDate('date_checkout', '>=', $dateFrom);
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->skip(($this->page - 1) * $this->perPage)
            ->take($this->perPage)
            ->get();

        $this->rooms = $this->rooms->concat($query);
    }


    protected function parseDateRange()
    {
        if (!$this->dateFromTo)
            return [null, null];


        $values = explode(' to ', $this->dateFromTo);
        if (count($values) < 2)
            return [null, null];

        [$start, $end] = $values;

        try {
            $startDate = \Carbon\Carbon::createFromFormat('Y-m-d', trim($start));
            $endDate = \Carbon\Carbon::createFromFormat('Y-m-d', trim($end));
        } catch (\Exception $e) {
            return [null, null];
        }

        return [$startDate, $endDate];
    }


    public function render()
    {
        [$dateFrom, $dateTo] = $this->parseDateRange();

        $totalCount = Room::where('actived', 1)
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('beds', 'like', '%' . $this->search . '%')
                        ->orWhere('meters', 'like', '%' . $this->search . '%')
                        ->orWhere('price', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->peoples, function ($q) {
                $q->where('peoples', '>=', $this->peoples);
            })
            ->when($dateFrom && $dateTo, function ($q) use ($dateFrom, $dateTo) {
                $q->whereDoesntHave('reservations', function ($sub) use ($dateFrom, $dateTo) {
                    $sub->whereDate('date_checkin', '<=', $dateTo)
                        ->whereDate('date_checkout', '>=', $dateFrom);
                });
            })
            ->count();

        $hasMore = $totalCount > $this->rooms->count();

        return view('livewire.admin.room-grid', [
            'rooms' => $this->rooms,
            'hasMore' => $hasMore,
            'componentId' => $this->getId()
        ]);
    }
}
