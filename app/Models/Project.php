<?php

namespace App\Models;

use App\Enums\ProjectStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
    ];
    protected $casts = [
        'status' => ProjectStatus::class, // Cast to Enum
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'project_user');
    }

    public function timesheets()
    {
        return $this->hasMany(Timesheet::class);
    }

    public function attributeValues()
    {
        return $this->morphMany(AttributeValue::class, 'entity');
    }
}
