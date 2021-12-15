@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">Lab Test Details</div>
    </div>
@endsection
@section('title', 'Lab Test Details')
@section('icon', "pencil")
@section('subtitle', 'Lab Test Details')

@section('content')

    <br><br>
    <div class="ui one column doubling stackable grid container">
        <div>
            <button onclick="return window.history.go(-1);" class="btn btn-primary ui button blue">
                Back
            </button>
        </div>
        <div class="column admin_basic_detail1">
            <div class="ui very padded segment">
                <div  class="page_title">
                    <h2>Basic Details</h2>
                </div>
                <table class="ui table">
                    <thead>
                    <tbody>
                        <tr>
                            <th>Title</th>
                            @if(\Auth::user()->isAdmin())
                                <th>Reported By</th>
                            @endif

                        </tr>
                        <tr>
                            <td>{{ $lab_test->title }}</td>
                            @if(\Auth::user()->isAdmin())
                                <td>{{ isset($lab_test->user->name ) ? $lab_test->user->name  : ""}}</td>
                            @endif
                        </tr>
                        <tr>
                            <th>Description</th>
                        </tr>
                            <tr>
                                <td>{!! $lab_test->description !!}</td> </tr>

                    </tbody>
                </table>
                <div class="row">
                    <div class="col-md-12">
                        <div class="title">
                            <div class="space10"></div>
                            <div class="page_title"><h2>Replies</h2></div>
                            <div class="divider space10"></div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <div class="comment_main_list">
                                <div class="comment_listing">
                                    <ul>
                                        @foreach($lab_test->replies as $reply)
                                            <li>
                                                <div class="header">
                                                    {{ $reply->user->name }}
                                                </div>
                                                <div class="time">
                                                    {{ $reply->created_at }}
                                                </div>
                                                <div class="panel-body">
                                                    {{ $reply->message }}
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                   @if($lab_test->status != \App\LabTest::STATUS_RESOLVED)
                                        @if(\Auth::user()->isAdmin() || $lab_test->created_by == \Auth::user()->id)
                                            <div class="comment_wrapper">
                                                <form  method="post" id="reply_form" action="{{ route('Laralum::lab_test.send_reply', ['lab_test_id' => $lab_test->id]) }}">
                                                    {!! csrf_field() !!}
                                                    <textarea class="form-control" name="message"></textarea>
                                                    <button type="submit" class="btn btn-primary ui button blue">Send</button>
                                                </form>
                                            </div>
                                        @endif
                                   @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection