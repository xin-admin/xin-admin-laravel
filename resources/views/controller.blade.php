{!! '<?php' !!}

namespace App\Http\Controllers\Admin\{{ $controllerPath }};

use App\Http\Controllers\Admin\Controller;
use App\Models\{{ $modelPath }}\{{ $modelName }};
use Illuminate\Http\JsonResponse;

class {{ $controllerName }} extends Controller
{

    // 权限标识
    protected string $authName = '{{ $authName }}';

    // 模型
    protected string $model = '{{ $modelName }}';

    // 查询字段
    protected array $searchField = [
        @foreach($searchField as $key => $value)
            '{{ $key }}' => '{!! $value !!}',
        @endforeach
    ];

    // 快速查询字段
    protected array $quickSearchField = [@foreach($quickSearchField as $value) '{!! $value !!}', @endforeach];

    // 验证
    protected array $rule = [
        @foreach($rules as $key => $value)
            '{{ $key }}' => '{!! $value !!}',
        @endforeach
    ];

    // 错误提醒
    protected array $message = [
        @foreach($message as $key => $value)
            '{{ $key }}' => '{!! $value !!}',
        @endforeach
    ];

    /**
    * 若需重写新增、查看、编辑、删除等方法，请复制 @see \app\Http\Controllers\Admin\Controller 中对应的方法至此进行重写
    */

}
