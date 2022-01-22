@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">Bills List</div>
    </div>
@endsection
@section('title', 'Bills')
@section('icon', "pencil")
@section('subtitle', 'List of all Bills')
@section('content')
    <div class="ui one column doubling stackable grid">
        <div class="column">
            <div class="ui very padded segment table_header_row table-responsive" id="bills_list">
           

               @include('laralum.bills._list')
            </div>
        </div>
    </div>
@endsection


@section('fsfsfsd')
<script type="text/javascript"> 
    $document.ready(function() {
        $("#all_search_bill_from_date").change(function(){
            let val_bill_from_date =  $("#all_search_bill_from_date").val();
            let val_bill_to_date =  $("#all_search_bill_to_date").val();
            tableSearch();
        })

        $("#all_search_bill_to_date").change(function(){
            let val_bill_from_date =  $("#all_search_bill_from_date").val();
            let val_bill_to_date =  $("#all_search_bill_to_date").val();
            tableSearch();
        })
    })
    function tableSearch() {
        let val_bill_from_date =  $("#all_search_bill_from_date").val();
        let val_bill_to_date =  $("#all_search_bill_to_date").val();

    }
</script>
@endsection