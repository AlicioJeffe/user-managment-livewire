@if (session()->has('message-error'))
<br>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('message-error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if (session()->has('message-success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('message-success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<br>
@endif      