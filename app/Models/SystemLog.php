<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemLog extends Model
{
    protected $table = 'system_logs';

    protected $fillable = [
        'level','message','description','context','file','line','stack',
        'logged_by','user_id','ip_address','url','method','headers','payload',
        'host','env'
    ];

    // Accessors for convenient decoding
    public function getContextAttribute($value)
    {
        return $value ? json_decode($value, true) : null;
    }

    public function getHeadersAttribute($value)
    {
        return $value ? json_decode($value, true) : null;
    }

    public function getPayloadAttribute($value)
    {
        return $value ? json_decode($value, true) : null;
    }

    // Mutators to ensure JSON strings are saved
    public function setContextAttribute($value)
    {
        $this->attributes['context'] = $value ? json_encode($value, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) : null;
    }

    public function setHeadersAttribute($value)
    {
        $this->attributes['headers'] = $value ? json_encode($value, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) : null;
    }

    public function setPayloadAttribute($value)
    {
        $this->attributes['payload'] = $value ? json_encode($value, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) : null;
    }
}
