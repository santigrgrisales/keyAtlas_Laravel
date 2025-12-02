<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class System extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}
