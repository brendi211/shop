@extends('catalog-old.layout')

@section('style')

    <link rel="stylesheet" href="{{ asset('catalog/css/products.css') }}">

@stop

@section('content')

    <div class="container">

        <div class="products">

            <h1 class="page-header">@translate('Результати пошуку'): "<strong class="text-primary">{{ $search_string }}</strong>"</h1>


            @forelse($products->chunk(4) as $chunk)
                <div class="row" {!! !$loop->last ? 'style="margin-bottom: 30px"' : '' !!}>
                    @foreach($chunk as $item)
                        @include('catalog-old.product.chunk', ['chunks' => 3, 'item' => $item])
                    @endforeach
                </div>
            @empty
                <h4 class="centered">@translate('Тут порожньо')</h4>
            @endforelse


            {{ $products->links('catalog.pagination.default') }}

        </div>
    </div>

@stop

@section('script')

    <script src="{{ asset('catalog/js/products.js') }}"></script>

    @include('catalog-old.assets.cart_scripts')

@stop
