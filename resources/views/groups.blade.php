@extends('layouts.master')

@section('content')
    <chat-view
        chat-type="group"
        :chat-info="{{ $group_info }}"
        :initial-messages="{{ $messages }}"
        :initial-users="{{ $users }}"
        :current-user="{ id: {{ $current_user_id }}, name: '{{ $current_user_first_name }}' }"
    ></chat-view>
@endsection
