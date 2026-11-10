<?php

namespace App\Models;

use App\Utils\RuleManager;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoEquipo extends Model
{
    use HasFactory;

    protected $table = 'tipos_equipos';
    
    protected $fillable = [
        'nombre',
        'intervalo_mantenimiento',
        'estado',
        'userinsert',
        'userupdate',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'intervalo_mantenimiento' => 'integer',
    ];

    protected $appends = [
        'isactive',
        'statename',
    ];

    public function getIsactiveAttribute()
    {
        return RuleManager::getIsActive($this->estado);
    }

    public function getStatenameAttribute()
    {
        return RuleManager::getStateName($this->estado);
    }

    // Un tipo de equipo tiene muchos equipos
    public function equipos(): HasMany
    {
        return $this->hasMany(Equipo::class, 'tipo_equipo_id');
    }
}
