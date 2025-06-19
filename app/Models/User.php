<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
use App\Models\Position;
use App\Models\Company;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use HasRoles;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'salary',
        'position_id',
        'hired_date',
        'phone_number',
        'address',
        'status',
        'photo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getNameAttribute()
    {
        return "{$this->fistname} {$this->lastname}";
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }

    public function role()
    {
        return $this->belongsToMany(Role::class, 'model_has_roles', 'model_id', 'role_id')->first();
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function requests()
    {
        return $this->hasMany(RequestLeave::class, 'employee_id');
    }

    public function overtimes()
    {
        return $this->hasMany(Overtime::class, 'employee_id');
    }
}
