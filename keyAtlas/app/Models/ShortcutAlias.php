<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShortcutAlias extends Model
{
    use HasFactory;

    protected $fillable = [
        'shortcut_id',
        'alias'
    ];

    public function shortcut()
    {
        return $this->belongsTo(Shortcut::class);
    }
}
