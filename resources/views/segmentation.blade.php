@section('page_name')
    Customer Segmentation Update
@stop

@section('scripts')
    <script>

    </script>
@stop

@section('stylesheets')

@stop

@section('content')
    <div class="frame">
        <div class="bit-2">
            <div class="container">
                <div class="container_title blue">
                    Segmentation customer update
                </div>
                <div class="container_content">
                    @if ($customer)
                        Customer name: <b>{{ $customer->getCustomerName() }}</b><br>
                        Contact name: <b>{{ $customer->contactName }}</b><br>
                        Address: <b>{{ $customer->address }}</b><br>
                        City: <b>{{ $customer->city }}</b><br>
                        Email: <b>{{ $customer->email }}</b><br>
                        CIF: <b>{{ $customer->cifnif }}</b><br>
                        <br><br>
                        <form method="get" action="">
                            <input type="hidden" name="id" value="{{ $customer->id }}"/>
                            Set to: <select name="type">
                                @foreach(CustomerType::all() as $type)
                                    <option value="{{ $type->id }}" @if ($customer->type == $type->id) selected="selected" @endif>{{ $type->type }}</option>
                                @endforeach
                            </select>
                            <input class="btn btn-green" type="submit" value="Update & show next customer"/>
                        </form>
                    @else
                        You're done! All customers have been updated
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop