<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Collection extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description'
    ];

    public function shortcuts()
    {
        return $this->belongsToMany(Shortcut::class, 'collection_shortcuts');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
