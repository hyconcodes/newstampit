<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stamp extends Model
{
    protected $fillable = [
        'type',
        'name',
        'original_image',
        'processed_image',
        'background_removed',
        'uploaded_by'
    ];

    protected $casts = [
        'background_removed' => 'boolean',
    ];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getImageUrlAttribute()
    {
        return $this->original_image ? $this->original_image : null;
    }
}
