<?php

namespace App\Models;

use App\Utils\RuleManager;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Equipo extends Model
{
    use HasFactory;

    protected $table = 'equipos';
    
    protected $fillable = [
        'codigo',
        'tipo_equipo_id',
        'horometro_actual',
        'horometro_ultimo_mantenimiento',
        'estado',
        'userinsert',
        'userupdate',
    ];

    protected $hidden = [
        'dateinsert',
        'dateupdate'
    ];

    protected $casts = [
        'tipo_equipo_id' => 'integer',
        'horometro_actual' => 'decimal:2',
        'horometro_ultimo_mantenimiento' => 'decimal:2',
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

    // Un equipo pertenece a un tipo de equipo
    public function tipoEquipo(): BelongsTo
    {
        return $this->belongsTo(TipoEquipo::class, 'tipo_equipo_id');
    }

    // Un equipo tiene muchos mantenimientos
    public function mantenimientos(): HasMany
    {
        return $this->hasMany(Mantenimiento::class, 'equipo_id');
    }
}
