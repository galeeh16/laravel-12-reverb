<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class Notification extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'user_id', 'title', 'message', 'is_read'
    ];
}