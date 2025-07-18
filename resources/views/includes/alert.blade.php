<style>
.alert {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 1rem;
    margin-bottom: 0.5rem;
    border: 1px solid transparent;
    border-radius: 0.25rem;
    min-width: 300px;
    max-width: 400px;
    z-index: 9999;
    box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.1);
    animation: slideIn 0.3s ease-in, fadeOut 13s forwards;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes fadeOut {
    0% { opacity: 1; }
    80% { opacity: 1; }
    100% { 
        opacity: 0;
        transform: translateX(100%);
    }
}

.alert-success {
    color: #ffffff;
    background-color: #00b74a;
    /* border-color: #badbcc; */
}

.alert-danger {
    color: #ffffff;
    background-color: #fd0015;
    /* border-color: #f5c2c7; */
}

.alert-warning {
    color: #ffffff;
    background-color: #fcc100;
    /* border-color: #ffecb5; */
}

.alert-info {
    color: #ffffff;
    background-color: #00cffe;
    /* border-color: #b6effb; */
}

.alert-dismissible {
    padding-right: 3rem;
}

.btn-close {
    position: absolute;
    top: 0;
    right: 0;
    padding: 1.25rem 1rem;
    background: transparent;
    border: 0;
    font-size: 1.5rem;
    cursor: pointer;
    opacity: 0.5;
    transition: opacity 0.15s ease-in-out;
}

.btn-close:hover {
    opacity: 1;
}

.btn-close::before {
    content: "×";
}

.alert ul {
    margin: 0;
    padding-left: 1.5rem;
}

.alert-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
}
</style>

<div class="alert-container">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
</div>
