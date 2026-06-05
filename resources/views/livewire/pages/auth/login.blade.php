<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();
        $this->form->authenticate();
        Session::regenerate();
        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div style="font-family: 'Plus Jakarta Sans', 'Figtree', sans-serif;">
    <style>
        /* ── Logo area ── */
        .login-logo-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 28px;
        }
        .login-logo-text {
            font-size: 1.6rem;
            font-weight: 800;
            color: #1a1a3e;
            letter-spacing: -0.04em;
            line-height: 1;
        }
        .login-logo-text span {
            color: #7c3aed;
        }
        .login-logo-sub {
            font-size: 0.55rem;
            font-weight: 700;
            letter-spacing: 0.18em;
            color: #8b8baa;
            text-transform: uppercase;
            display: block;
            margin-top: 1px;
        }

        /* ── Title ── */
        .login-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: #1a1a3e;
            letter-spacing: -0.03em;
            margin: 0 0 8px 0;
            line-height: 1.2;
        }
        .login-subtitle {
            font-size: 0.875rem;
            color: #6b7280;
            margin: 0 0 28px 0;
            line-height: 1.5;
        }

        /* ── Form ── */
        .login-form .form-group {
            margin-bottom: 16px;
        }
        .login-form label {
            display: block !important;
            font-size: 0.8rem !important;
            font-weight: 600 !important;
            color: #374151 !important;
            margin-bottom: 6px !important;
            letter-spacing: 0 !important;
            text-transform: none !important;
            position: static !important;
            float: none !important;
            transform: none !important;
            background: transparent !important;
            padding: 0 !important;
        }
        .login-form input[type="email"],
        .login-form input[type="password"] {
            appearance: none !important;
            -webkit-appearance: none !important;
            width: 100% !important;
            display: block !important;
            box-sizing: border-box !important;
            background: #f0f1f8 !important;
            border: 1.5px solid transparent !important;
            border-radius: 14px !important;
            padding: 13px 16px !important;
            font-size: 0.9rem !important;
            color: #1a1a3e !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            font-weight: 500 !important;
            transition: border-color 0.18s, box-shadow 0.18s, background 0.18s !important;
            outline: none !important;
            margin: 0 !important;
        }
        .login-form input:-webkit-autofill,
        .login-form input:-webkit-autofill:hover,
        .login-form input:-webkit-autofill:focus {
            -webkit-box-shadow: 0 0 0 30px #f0f1f8 inset !important;
            -webkit-text-fill-color: #1a1a3e !important;
        }
        .login-form input[type="email"]:focus,
        .login-form input[type="password"]:focus {
            background: #fff !important;
            border-color: #7c3aed !important;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.12) !important;
        }
        .login-form input::placeholder {
            color: #aaa !important;
            font-weight: 400 !important;
        }

        /* ── Button ── */
        .login-form .login-btn {
            appearance: none !important;
            -webkit-appearance: none !important;
            width: 100% !important;
            display: block !important;
            border: none !important;
            cursor: pointer !important;
            margin-top: 8px !important;
            background: linear-gradient(135deg, #5b21b6 0%, #7c3aed 60%, #6d28d9 100%) !important;
            color: #fff !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            font-size: 0.95rem !important;
            font-weight: 700 !important;
            letter-spacing: 0.01em !important;
            padding: 14px 20px !important;
            border-radius: 14px !important;
            text-align: center !important;
            box-shadow: 0 4px 18px rgba(109, 40, 217, 0.35) !important;
            transition: transform 0.15s, box-shadow 0.15s, background 0.15s !important;
        }
        .login-form .login-btn:hover {
            background: linear-gradient(135deg, #4c1d95 0%, #6d28d9 100%) !important;
            box-shadow: 0 6px 24px rgba(109, 40, 217, 0.45) !important;
            transform: translateY(-1px) !important;
        }
        .login-form .login-btn:active {
            transform: translateY(0) !important;
            box-shadow: 0 2px 10px rgba(109, 40, 217, 0.3) !important;
        }
        .login-form .login-btn:focus {
            outline: none !important;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.3) !important;
        }
    </style>

    <!-- Logo Row -->
    <div class="login-logo-row">
    <div style="display: flex; align-items: center; gap: 10px;">
        <img src="{{ asset('images/abasa-hr-logo.svg') }}" alt="Abasa HR Consulting" style="height: 40px; width: auto;">
    </div>
</div>

    <!-- Title -->
    <h1 class="login-title">Selamat Datang</h1>
    <p class="login-subtitle">Masuk untuk mengakses dashboard analitik responden.</p>

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-4 text-sm font-medium text-green-600">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit="login" class="login-form">
        <div class="form-group">
            <label for="email">Username</label>
            <input
                wire:model="form.email"
                id="email"
                type="email"
                name="email"
                required
                autofocus
                autocomplete="username"
                placeholder="Masukkan username admin"
            />
            <x-input-error :messages="$errors->get('form.email')" class="mt-1" />
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input
                wire:model="form.password"
                id="password"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                placeholder="••••••••"
            />
            <x-input-error :messages="$errors->get('form.password')" class="mt-1" />
        </div>

        <button type="submit" class="login-btn mt-4">
            Masuk ke Dashboard →
        </button>
    </form>

    <!-- Bottom Link -->
    <div class="text-center mt-4">
        <a href="/" class="text-xs text-purple-600 hover:text-purple-900 hover:underline transition" wire:navigate>
            ← Kembali ke Halaman Skrining
        </a>
    </div>
</div>