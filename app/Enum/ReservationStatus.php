<?php

namespace App\Enum;

enum ReservationStatus: string
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case CANCELED = 'canceled';

    public function label(): string
    {
        return ucfirst($this->value);
    }

    public function badge(): string
    {
        return match ($this) {
            self::PENDING => '<span class="px-2 py-1 rounded text-xs bg-yellow-100 text-yellow-800">Pending</span>',
            self::CONFIRMED => '<span class="px-2 py-1 rounded text-xs bg-green-100 text-green-800">Confirmed</span>',
            self::CANCELED => '<span class="px-2 py-1 rounded text-xs bg-red-100 text-red-800">Canceled</span>',
        };
    }

    public static function values(): array
    {
        return [
            'pending' => '<span class="px-2 py-1 rounded text-xs bg-yellow-100 text-yellow-800">Pending</span>',
            'confirmed' => '<span class="px-2 py-1 rounded text-xs bg-green-100 text-green-800">Confirmed</span>',
            'canceled' => '<span class="px-2 py-1 rounded text-xs bg-red-100 text-red-800">Canceled</span>',
        ];
    }


    public function labelPowergridFilter(): string
    {
        return $this->label();
    }
}
