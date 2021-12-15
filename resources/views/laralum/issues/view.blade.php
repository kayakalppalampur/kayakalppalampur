@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        @if($issue->type == \App\Issue::TYPE_ISSUE)
        <a class="section" href="{{ route('Laralum::issues') }}">{{ trans('laralum.issues') }}</a>
        @else
            <a class="section" href="{{ route('Laralum::queries') }}">{{ trans('laralum.queries') }}</a>
        @endif
        <i class="right angle icon divider"></i>
        <div class="active section">{{  trans('laralum.details') }}</div>
    </div>
@endsection
@section('title', $title)
@section('icon', "pencil")

@section('content')

    <div class="ui one column doubling stackable">
        <div class="column">
            <div class="ui very padded segment table_sec2 ">

                {{--<div  class="page_title table_top_btn">
                    <h2>Details</h2>
                </div>--}}

                <div class="issues_details_con">

                    <h2 class="bs_title">{{ $issue->title }}</h2>
                    <h3 class="created_at"> {{ $issue->created_at }} </h3>
                    <h3 class="reported_on">
                        @if(\Auth::user()->isAdmin())
                           @if($issue->type == \App\Issue::TYPE_QUERY)
                                     {{ $issue->name }}
                                @else
                                    {{ isset($issue->user->name ) ? $issue->user->name  : ""}}
                                @endif
                        @endif
                    </h3>
                    <p class="bs_description">{!! $issue->description !!}</p>

                </div>


                {{--<table class="ui table table_cus_v last_row_bdr">
                    <thead>
                    <tbody>
                        <tr>
                            <th>{{ $issue->type == \App\Issue::TYPE_QUERY ? "Subject" : "Title" }}</th>
                            @if(\Auth::user()->isAdmin() || $issue->type == \App\Issue::TYPE_QUERY)
                                <th>Reported By</th>
                            @endif

                        </tr>
                        <tr>
                            <td>{{ $issue->title }}</td>
                            @if(\Auth::user()->isAdmin())
                                <td>@if($issue->type == \App\Issue::TYPE_QUERY)
                                        {{ $issue->name }}
                                    @else
                                        {{ isset($issue->user->name ) ? $issue->user->name  : ""}}
                                    @endif
                                </td>
                            @endif
                        </tr>
                        <tr>
                            <th>Reported On</th>
                            @if($issue->type == \App\Issue::TYPE_QUERY)
                                <th>Email</th>
                            @endif

                        </tr>
                        <tr>
                            <td>{{ $issue->created_at }}</td>
                            @if($issue->type == \App\Issue::TYPE_QUERY)
                                <th>{{ $issue->email_id }}</th>
                            @endif
                        </tr>
                        <tr>
                            <th>Description</th>
                        </tr>
                        <tr>
                            <td>{!! $issue->description !!}</td>
                        </tr>

                    </tbody>
                </table>--}}


                <div class="reply_wrapperr">

                    <div class="page_title"><h2>Replies</h2></div>

                    <div class="reply_list_con">
                        <div class="comment_main_list">
                            <div class="comment_listing">
                                <ul>
                                    @if(count($issue->getAllReplies()) > 0)
                                    @foreach($issue->getAllReplies() as $reply)
                                        <li>
                                            <div class="header">
                                                {{ isset($reply->user->name) ?  $reply->user->name : " "}}
                                            </div>
                                            <div class="time">
                                                {{ $reply->created_at != null ? $reply->created_at->setTimezone(env('TIMEZONE'))->toDateTimeString() : ""}}
                                            </div>
                                            <div class="panel-body">
                                                {{ $reply->message }}
                                            </div>
                                        </li>
                                    @endforeach
                                        @else
                                        <li>No Replies</li>
                                    @endif
                                </ul>
                            </div>

                               @if($issue->status != \App\Issue::STATUS_RESOLVED)
                                    @if(\Auth::user()->isAdmin() || $issue->created_by == \Auth::user()->id)
                                        <div class="comment_wrapper">
                                            <form  method="post" id="reply_form" action="{{ route('Laralum::issue.send_reply', ['issue_id' => $issue->id]) }}">
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
@endsection