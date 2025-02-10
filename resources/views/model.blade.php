{!! '<?php' !!}

namespace App\Models\Dict;

use App\Models\BaseModel;

class {{ $modelName }} extends BaseModel
{
    protected $table = '{{ $tableName }}';

    protected $fillable = [];

}
