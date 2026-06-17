@if(session('success'))
    <x-alert
        type="success"
        :message="session('success')" />
@endif

@if(session('error'))
    <x-alert
        type="error"
        :message="session('error')" />
@endif

@if(session('warning'))
    <x-alert
        type="warning"
        :message="session('warning')" />
@endif

@if ($errors->any())
    <x-alert
        type="error"
        :message="implode(' | ', $errors->all())" />
@endif