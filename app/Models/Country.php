<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'iso3',
        'iso2',
        'numeric_code',
        'phone_code',
        'capital',
        'currency',
        'tld',
        'native',
        'region',
        'subregion',
        'timezones',
        'translations',
        'latitue',
        'longitude',
        'emoji',
        'emojiU'
    ];
}
