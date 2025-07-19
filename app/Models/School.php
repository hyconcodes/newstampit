<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class School extends Model
{
    use HasRoles;

    protected $fillable = ['name'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function students()
    {
        return $this->hasMany(User::class)->role('student');
    }

    public function schoolFeesAdmins()
    {
        return $this->hasMany(User::class)->role('school fees admin');
    }

    public function igrsAdmins()
    {
        return $this->hasMany(User::class)->role('igrs admin');
    }
}
