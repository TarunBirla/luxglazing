<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Throwable;

class SystemSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'setting_key',
        'setting_value',
        'group',
    ];

    public static function get($key, $default = null)
    {
        try {
            $setting = static::where('setting_key', $key)->first();
            return $setting ? $setting->setting_value : $default;
        } catch (Throwable $e) {
            return $default;
        }
    }

    public static function set($key, $value, $group = 'general')
    {
        try {
            return static::updateOrCreate(
                ['setting_key' => $key],
                ['setting_value' => $value, 'group' => $group]
            );
        } catch (Throwable $e) {
            return null;
        }
    }
}
