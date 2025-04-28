/**
* {{ $name }} Domain
*/
export interface {{ $domainName  }} {
@foreach($columns as $column)
    {{ $column['name'] }}?: {{ $column['type'] }};
@endforeach
}