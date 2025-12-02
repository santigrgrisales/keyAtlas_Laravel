<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description'
    ];

    // Una categoría pertenece a un sistema (Windows, MacOS, Linux)
    public function system()
    {
        return $this->belongsTo(System::class);
    }

    // Una categoría tiene muchos shortcuts
    public function shortcuts()
    {
        return $this->hasMany(Shortcut::class);
    }
}
