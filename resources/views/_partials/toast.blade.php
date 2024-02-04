@if (session()->has(['type', 'title', 'message']))
    <div class="bs-toast toast bg-{{ session('type') }} fade show position-absolute m-2" role="alert" aria-live="assertive" aria-atomic="true"
        style="top: 2%; right: 1%; opacity: 0.8;">
        <div class="toast-header">
            <i class='bx bx-bell me-2'></i>
            <div class="me-auto fw-medium">{{ session('title') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">{{ session('message') }}</div>
    </div>
@endif
