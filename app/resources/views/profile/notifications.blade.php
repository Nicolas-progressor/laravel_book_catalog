@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Уведомления</h1>
    @if($notifications->where('is_read', false)->count() > 0)
        <form method="post" action="{{ route('app_notifications_mark_all_read') }}">
            @csrf
            <button type="submit" class="btn btn-outline-secondary btn-sm">
                Отметить все как прочитанные
            </button>
        </form>
    @endif
</div>

@if($notifications->isEmpty())
    <div class="alert alert-info">У вас нет уведомлений</div>
@else
    <div class="list-group">
        @foreach($notifications as $notification)
            <div class="list-group-item list-group-item-action {{ $notification->is_read ? '' : 'list-group-item-primary' }}">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="ms-2 me-auto">
                        <div class="fw-bold">{{ $notification->title }}</div>
                        @if($notification->message)
                            {{ $notification->message }}
                        @endif
                        <small class="text-muted d-block mt-1">
                            {{ $notification->created_at->format('d.m.Y H:i') }}
                        </small>
                    </div>
                    @if(!$notification->is_read)
                        <form method="post" action="{{ route('app_notification_read', $notification->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                Отметить как прочитанное
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-3">
        {{ $notifications->links() }}
    </div>
@endif
@endsection