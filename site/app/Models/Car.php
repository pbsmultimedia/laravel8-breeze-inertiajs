<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function maintenance()
    {
        return $this->hasMany(Maintenance::class);
    }

    public function getNameAttribute()
    {
        // return $this->brand()->name;
        return 'setter';
    }
}
