import XinTable from '@/components/Xin/XinTable';
import { XinTableColumn } from '@/components/Xin/XinTable/typings';
import { {{ $domainName  }} } from '@/domain/{{ $domainFileName }}';

export default () => {
    @include('shared.errors')

    return (

        {!! '<XinTable<' !!}{{ $domainName }}{!! '>' !!}
            accessName={'{{ $abilitiesPrefix }}'}
            api={'{{ $routePrefix }}'}
            columns={columns}
            rowKey={'role_id'}
            tableProps={tableProps}
        />

    )
}