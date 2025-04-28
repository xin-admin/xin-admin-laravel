{!! '<?php' !!}

namespace {{ $modelNamespace  }};

use Illuminate\Database\Eloquent\Model;

class {{ $modelName }} extends Model
{
    protected $table = '{{ $tableName }}';

    protected $fillable = [
@foreach ($fillable as $item)
        @if($item['sql'])
            '{{ $item['name'] }}',
        @endif
@endforeach
    ];

}
