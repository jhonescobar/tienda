<?php
namespace App\Http\Modulos;

use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TimestampSerializableTrait;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class MainModel extends Model implements AuditableContract {
    use Auditable;
    use TimestampSerializableTrait;

    protected $connection = 'mysql';

    public $timestamps = true;
}