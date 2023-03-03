@php
    use Aqamarine\AlphaCruds\Components\SessionAlerts;
    $alert = new SessionAlerts();
@endphp

@if($alert->hasError())
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><em class="icon fa fa-ban"></em> Error!</h4>
        {!! $alert->displayError() !!}
    </div>
@endif

@if($alert->hasSuccess())
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><em class="icon fa fa-check"></em> Success!</h4>
        {!! $alert->displaySuccess() !!}
    </div>
@endif

@if($alert->hasWarning())
    <div class="alert alert-warning alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><em class="icon fa fa-exclamation-triangle"></em> Warning!</h4>
        {!! $alert->displayWarning() !!}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><em class="icon fa fa-check"></em> Error!</h4>
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif
