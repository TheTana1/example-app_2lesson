<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $label
 * @property string $slug
 */
class Role extends Model
{
    protected $table = 'roles';
    protected $fillable = [
        'label',
        'slug',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
