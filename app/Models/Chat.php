<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id_1',
        'user_id_2'
    ];

    public function users() : BelongsToMany {
        return $this->belongsToMany(User::class);
    }

    public function messages() : HasMany {
        return $this->hasMany(Message::class);
    }

    public function recipient() {
        if ($this->user_id_1===auth()->user()->id) {
            return User::findorFail($this->user_id_2);
        } else if($this->user_id_2===auth()->user()->id) {
            return User::findorFail($this->user_id_1);
        } else {
            return null;
        }
    }
}
