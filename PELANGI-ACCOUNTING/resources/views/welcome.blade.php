<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Elevon') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="{{ asset('fav.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Instrument Sans', ui-sans-serif, system-ui, -apple-system, sans-serif;
                background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
                color: #1b1b18;
                min-height: 100vh;
                padding: 2rem;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .container {
                max-width: 680px;
                width: 100%;
            }

            .logo {
                display: flex;
                justify-content: center;
                margin-bottom: 3rem;
            }

            .logo img {
                max-width: 200px;
                height: auto;
            }

            .cards-grid {
                display: grid;
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            @media (min-width: 768px) {
                .cards-grid {
                    grid-template-columns: repeat(2, 1fr);
                }
            }

            .card {
                background-color: #ffffff;
                padding: 2.5rem 2rem;
                border-radius: 0.75rem;
                box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
                border: 1px solid #bae6fd;
                text-decoration: none;
                color: inherit;
                display: block;
                transition: all 0.3s ease;
                position: relative;
            }

            .card:hover {
                box-shadow: 0 10px 15px -3px rgba(14, 165, 233, 0.1), 0 4px 6px -2px rgba(14, 165, 233, 0.05);
                border-color: #7dd3fc;
                transform: translateY(-4px);
            }

            .card.coming-soon {
                opacity: 0.7;
                cursor: not-allowed;
            }

            .card.coming-soon:hover {
                transform: none;
                box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            }

            .coming-soon-badge {
                position: absolute;
                top: 1rem;
                right: 1rem;
                background-color: #94a3b8;
                color: #ffffff;
                font-size: 0.6875rem;
                font-weight: 600;
                padding: 0.25rem 0.625rem;
                border-radius: 9999px;
                text-transform: uppercase;
                letter-spacing: 0.025em;
            }

            .icon-wrapper {
                width: 4.5rem;
                height: 4.5rem;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 1.5rem auto;
                transition: all 0.3s ease;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            }

            .icon-wrapper svg {
                width: 2.25rem;
                height: 2.25rem;
            }

            .card h2 {
                font-size: 1.375rem;
                font-weight: 600;
                margin-bottom: 0.625rem;
                text-align: center;
                color: #0c4a6e;
            }

            .card p {
                font-size: 0.9375rem;
                color: #64748b;
                text-align: center;
                line-height: 1.6;
                font-weight: 400;
            }

            .card.coming-soon .icon-wrapper {
                opacity: 0.6;
            }

            .card.coming-soon h2 {
                color: #64748b;
            }

            .card.coming-soon p {
                color: #94a3b8;
            }

            /* Accounting Card - Sky Blue */
            .card.accounting .icon-wrapper {
                background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
                color: #ffffff;
            }

            .card.accounting:hover .icon-wrapper {
                background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
                box-shadow: 0 10px 15px -3px rgba(14, 165, 233, 0.4);
            }

            /* Customer Card - Sky/Blue */
            .card.customer .icon-wrapper {
                background: linear-gradient(135deg, #38bdf8 0%, #0ea5e9 100%);
                color: #ffffff;
            }

            /* Delivery Card - Light Sky/Blue */
            .card.delivery .icon-wrapper {
                background: linear-gradient(135deg, #7dd3fc 0%, #38bdf8 100%);
                color: #ffffff;
            }

            @media (min-width: 1024px) {
                body {
                    padding: 3rem;
                }

                .logo img {
                    max-width: 240px;
                }

                .card {
                    padding: 3rem 2.5rem;
                }
            }

            @media (max-width: 767px) {
                .logo img {
                    max-width: 160px;
                }

                .card {
                    padding: 2rem 1.5rem;
                }
            }
        </style>
    </head>
    <body>
        <div class="container">
            <!-- Logo -->
            <div class="logo">
                <img src="{{ asset('logo.png') }}" alt="Elevon Logo">
            </div>

            <div class="cards-grid">
                <!-- Accounting Panel -->
                <a href="{{ url('/main') }}" class="card accounting">
                    <div class="icon-wrapper">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h2>Accounting</h2>
                    <p>Access financial management and business operations</p>
                </a>

                <!-- Customer Panel - Coming Soon -->
                <div class="card customer coming-soon">
                    <span class="coming-soon-badge">Coming Soon</span>
                    <div class="icon-wrapper">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h2>Customer</h2>
                    <p>View orders and track delivery status</p>
                </div>

                <!-- Delivery Panel - Coming Soon -->
                <!-- <div class="card delivery coming-soon">
                    <span class="coming-soon-badge">Coming Soon</span>
                    <div class="icon-wrapper">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
                        </svg>
                    </div>
                    <h2>Delivery</h2>
                    <p>Manage dispatch and logistics operations</p>
                </div> -->
            </div>
        </div>
    </body>
</html>
