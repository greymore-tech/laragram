@extends('layouts.master')

@section('content')
    <chat-view
        chat-type="channel"
        :chat-info="{{ $channel_info }}"
        :initial-messages="{{ $messages }}"
        :current-user="{ id: {{ $current_user_id }}, name: '{{ $current_user_first_name }}' }"
    ></chat-view>
@endsection
