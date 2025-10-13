<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Notifications\AdminResetPassword;

class Administrador extends Authenticatable
{
    use HasFactory, Notifiable;
    use HasRoles;

    protected $table = 'administrador';
    public $timestamps = false;

    protected $guard_name = 'api';

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new AdminResetPassword($token));
    }


}
