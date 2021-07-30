@extends('products.layout')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">

                <h2 style="margin-top: 5%;margin-bottom: 5%">Bot keyword list</h2>
            </div>
            {{-- <div class="pull-right">
                <a class="btn btn-success" href="{{ route('keyword.create') }}"> Create New Product</a>
            </div> --}}
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-bordered">
        <tr>
            <th>No</th>
            <th>Name</th>
            <th>Search count</th>
            <th>last search</th>
            <th width="280px">Action</th>
        </tr>

        @foreach ($products as $product)
        @php
            $date = \Carbon\Carbon::parse($product->updated_at);
            $now = \Carbon\Carbon::now();
        @endphp
        <tr>
            <td>{{ ++$i }}</td>
            <td>{{ $product->name }}</td>
            <td>{{ $product->count }} times</td>
            <td>{{  $date->diffForHumans($now) }}</td>
            <td>
                <form action="{{ route('keyword.destroy',$product->id) }}" method="POST">

                    <a class="btn btn-info" href="{{ route('keyword.show',$product->id) }}">Show</a>

                    {{-- <a class="btn btn-primary" href="{{ route('keyword.edit',$product->id) }}">Edit</a> --}}

                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>

    {!! $products->links() !!}

@endsection
