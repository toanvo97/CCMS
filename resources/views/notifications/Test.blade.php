@if ($setupCompleted = \App\Models\Setting::setupCompleted())
@component('mail::message')
@endif

{{ trans('mail.test_mail_text') }}

Thanks,
Okuro-IT
@if ($setupCompleted)
@endcomponent
@endif
