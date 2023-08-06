<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_name',
        'description'
    ];

    public function courses() : HasMany {
        return $this->hasMany(Course::class);
    }

    public function teachers() : HasMany {
        return $this->hasMany(Teacher::class);
    }

    public function students() : HasMany {
        return $this->hasMany(Student::class);
    }

    public function deleteDepartment() {
        $this->delete();
    }
}
