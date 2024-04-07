@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Search Results for "{{ $query }}"</h2>
    @if($results->isEmpty())
        <p>No results found matching your query.</p>
    @else
        @foreach($results as $result)
            <div class="search-result">
                @if($result instanceof \App\Models\Branch)
                    <h3>Branch: {{ $result->name }}</h3>
                    <p>{{ $result->address }}, {{ $result->city }}</p>
                @elseif($result instanceof \App\Models\Auto)
                    <h3>Auto: {{ $result->name }}</h3>
                @endif
            </div>
        @endforeach
    @endif
</div>
@endsection
