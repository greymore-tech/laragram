@extends('layouts.master')
@section('content')
    <chat-view
        chat-type="user"
        :chat-info="{{ $other_user_info }}"
        :initial-messages="{{ $messages }}"
        :initial-users="{{ $users }}"
        :current-user="{ id: {{ $current_user_id }} }"
    ></chat-view>
@endsection
