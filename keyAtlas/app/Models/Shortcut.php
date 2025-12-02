<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shortcut extends Model
{
    use HasFactory;

    protected $fillable = [
        'system_id',
        'application_id',
        'category_id',
        'combination',
        'title',
        'description'
    ];

    public function system()
    {
        return $this->belongsTo(System::class);
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function aliases()
    {
        return $this->hasMany(ShortcutAlias::class);
    }

    public function collections()
    {
        return $this->belongsToMany(Collection::class, 'collection_shortcuts');
    }
}
