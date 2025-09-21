<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #2B599E;
            --primary-dark: #254b83;
            --secondary: #64748b;
            --secondary-dark: #5e6878;
            --success: #13A28F;
            --info: #3b82f6;
            --warning: #f59e0b;
            --light-bg: #f8fafc;
            --card-gradient: linear-gradient(135deg, #f5f7fa 0%, #eef1f6 100%);
            --dark-card-gradient: linear-gradient(135deg, #1E293B, #334155);
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--light-bg);
            min-height: 100vh;
        }

        .navbar {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95) !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .card-primary-gradient { background: linear-gradient(135deg, #3498db, #2980b9); }
        .card-secondary-gradient { background: linear-gradient(135deg, #64748b, #4b5563); }
        .card-success-gradient { background: linear-gradient(135deg, #13A28F, #0f8877); }
        .card-dark-gradient { background: linear-gradient(135deg, #2B599E, #254b83); }

        .btn {
            border-radius: 9999px;
            font-weight: 600;
            padding: 8px 16px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
            transform: translateY(-1px);
        }

        .btn-outline-secondary {
            border-color: var(--secondary);
            color: var(--secondary);
        }

        .btn-outline-secondary:hover {
            background-color: var(--secondary);
            border-color: var(--secondary);
            color: white;
        }
        .btn-outline-primary {
             color: var(--primary);
             border-color: var(--primary);
        }
        .btn-outline-primary:hover {
            background-color: var(--primary);
            color: #fff;
        }

        .table {
            background: white;
            border-radius: 12px;
            overflow: hidden;
        }

        .table-hover tbody tr:hover {
            background-color: var(--light-bg);
        }

        .badge {
            padding: 6px 10px;
            border-radius: 9999px;
            font-weight: 500;
        }
        .badge.bg-primary { background-color: var(--primary) !important; }
        .badge.bg-success { background-color: var(--success) !important; }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }
        .text-white-50 {
            color: rgba(255,255,255,0.5);
        }
    </style>
    @stack('head')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light">
            <div class="container">
                <a class="navbar-brand fw-bold fs-4" href="{{ url('/') }}">
                    <i class="fas fa-bolt text-primary me-2"></i>
                    {{ config('app.name', 'Laravel') }}
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto"></ul>
                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('plans.index') }}">
                                    <i class="fas fa-credit-card me-1"></i> Plans
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                        <span class="text-white fw-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                    </div>
                                    {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('dashboard') }}">Profile</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @if(session('success'))
                <div class="container">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="container">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            @endif

            @if(session('info'))
                <div class="container">
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="fas fa-info-circle me-2"></i>{{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.btn[data-loading]');
            buttons.forEach(button => {
                button.addEventListener('click', function() {
                    const loading = this.querySelector('.loading');
                    const text = this.querySelector('.btn-text');
                    if (loading && text) {
                        loading.classList.add('show');
                        text.style.display = 'none';
                        this.disabled = true;
                    }
                });
            });
        });
    </script>
    @yield('scripts')
</body>
</html>
