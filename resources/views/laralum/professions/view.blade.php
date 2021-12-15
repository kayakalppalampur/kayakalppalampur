@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <div class="active section">Profession Details</div>
    </div>
@endsection
@section('title', 'Profession Details')
@section('icon', "pencil")
@section('subtitle', 'Profession Details')

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
                            <th>Name</th>
                        </tr>
                        <tr>
                            <td>{{ $profession->name }}</td>
                        </tr>

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
                                        @foreach($profession->replies as $reply)
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

                                   @if($profession->status != \App\profession::STATUS_RESOLVED)
                                        @if(\Auth::user()->isAdmin() || $profession->created_by == \Auth::user()->id)
                                            <div class="comment_wrapper">
                                                <form  method="post" id="reply_form" action="{{ route('Laralum::profession.send_reply', ['profession_id' => $profession->id]) }}">
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