<?php
 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Enrollment extends Model
{
    const STATUS = [
        'in_progress' => 'in progress',
        'completed'   => 'completed',
    ];

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'course_id',
        'status',
    ];

     /**
     * Get the users assigned to the enrollment.
     */
    public function user(): HasMany
    {
        return $this->hasMany(User::class, 'id', 'user_id');
    }

    /**
     * Get the courses assigned to the enrollment.
     * 
     * 
     */
    public function course(): HasMany
    {
        return $this->hasMany(Course::class, 'id', 'course_id');
    }
}