<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Wilgucki\Csv\Traits\CsvCustomCollection;
use Wilgucki\Csv\Traits\CsvImportable;

class MemberOnboarding extends Model
{
//    use HasApiTokens, HasFactory, Notifiable;
//    use CsvImportable;
//    use CsvCustomCollection;
    public $timestamps =false;

    protected $fillable = [
        'user_id',
        'created_at',
        'onboarding_perentage',
        'count_applications',
        'count_accepted_applications'
    ];

}
