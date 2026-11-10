<?php

namespace App\Models;

use App\Utils\RuleManager;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mantenimiento extends Model
{
    use HasFactory;

    protected $table = 'mantenimientos';

    protected $fillable = [
        'equipo_id',
        'horometro',
        'fecha_mantenimiento',
        'descripcion',
        'estado',
        'userinsert',
        'userupdate',
    ];

    protected $hidden = [
        'dateinsert',
        'dateupdate'
    ];

    protected $casts = [
        'equipo_id' => 'integer',
        'horometro' => 'decimal:2',
        'fecha_mantenimiento' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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

    // Un mantenimiento pertenece a un equipo
    public function equipo(): BelongsTo
    {
        return $this->belongsTo(Equipo::class, 'equipo_id');
    }
}
