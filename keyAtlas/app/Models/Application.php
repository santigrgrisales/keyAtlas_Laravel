<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'system_id',
        'name',
        'description',
        'icon'
    ];

    public function system()
    {
        return $this->belongsTo(System::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function shortcuts()
    {
        return $this->hasMany(Shortcut::class);
    }
}
