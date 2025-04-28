{!! '<?php' !!}

namespace {{ $controllerNamespace }};

use App\Http\BaseController;
use App\Models\{{ $modelPath }}\{{ $modelName }};
use {{ $requestNamespace }}\{{ $requestName }};

use Xin\AnnoRoute\Attribute\RequestMapping;
@if ($crud['find'])use Xin\AnnoRoute\Attribute\Find;@endif
@if ($crud['update'])use Xin\AnnoRoute\Attribute\Update;@endif
@if ($crue['delete'])use Xin\AnnoRoute\Attribute\Delete;@endif
@if ($crue['create'])use Xin\AnnoRoute\Attribute\Create;@endif
@if ($crud['query'])use Xin\AnnoRoute\Attribute\Query;@endif

/**
 * {{ $name }} 控制器
 */
#[RequestMapping('{{ $routePrefix }}', '{{ $abilitiesPrefix }}')]
#[@if ($crud['query'])Create,@endif@if ($crud['update'])Update,@endif@if ($crue['delete'])Delete,@endif@if ($crue['create'])Query,@endif@if($crud['find'])Find,@endif]
class {{ $controllerName }} extends Controller
{
    // 当前控制器使用的模型
    protected string $model = {{ $modelName }}::class;
    // 当前控制器使用的表单请求
    protected string $formRequest = {{ $requestName }}::class;
    // 当前控制器的查询字段
    protected array $searchField = [
@foreach($searchField as $key => $value)
            '{{ $key }}' => '{!! $value !!}',
@endforeach
    ];
@isset ($quickSearchField)
    // 当前控制器的快速搜索字段
    protected array $quickSearchField = [@foreach($quickSearchField as $value) '{!! $value !!}', @endforeach];
@endisset
}