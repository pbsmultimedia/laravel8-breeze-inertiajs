<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Maintenance extends Model
{
    use HasFactory;

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }

    public function fetch($request) {
        $query = self::select(['maintenances.id', 'maintenances.cost', 'maintenances.date', 'cars.registration_plate', 'clients.name AS client_name', 'clients.email', 'brands.name AS brand_name'])
            ->join('cars', 'car_id', '=', 'cars.id')
            ->join('clients', 'cars.client_id', '=', 'clients.id')
            ->join('brands', 'cars.brand_id', '=', 'brands.id');

        $query->when($request->sort_by, function ($query, $value) {
            $query->orderBy($value, request('order_by', 'asc'));
        });

        $query->when($request->brand, function ($query, $value) {
            $query->where('brands.id', '=', $value);
        });

        return $query->paginate($request->page_size ?? 10);
    }
}
