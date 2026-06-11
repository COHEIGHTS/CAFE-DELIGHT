@php
    $address    = null;
    $formAction = route('addresses.store') . (request('redirect') ? '?redirect=' . request('redirect') : '');
    $formMethod = 'POST';
    $btnLabel   = 'Save Address';
@endphp

@include('addresses._form')