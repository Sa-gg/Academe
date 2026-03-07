<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academe — @yield('title', 'Dashboard')</title>

    {{-- Typography: Fraunces (editorial serif) + Plus Jakarta Sans (geometric sans) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300..900;1,9..144,300..900&family=Plus+Jakarta+Sans:wght@400..700&display=swap" rel="stylesheet">

    <style>
        /* ═══════════════════════════════════════════════════════════
           DESIGN TOKENS — Clean Modern
           Warm light palette · Dark sidebar · Indigo accent
           Inspired by Linear, Resend, Clerk, Raycast
           ═══════════════════════════════════════════════════════════ */
        :root {
            /* — Backgrounds — */
            --bg-page:            #F8F8F6;
            --bg-card:            #FFFFFF;
            --bg-elevated:        #FFFFFF;
            --bg-muted:           #F1F1EF;
            --bg-sidebar:         #111113;
            --bg-sidebar-hover:   #1C1C1F;
            --bg-sidebar-active:  rgba(99, 102, 241, 0.08);

            /* — Primary: Indigo — */
            --primary:            #6366F1;
            --primary-hover:      #4F46E5;
            --primary-soft:       rgba(99, 102, 241, 0.06);
            --primary-muted:      rgba(99, 102, 241, 0.10);
            --primary-ring:       rgba(99, 102, 241, 0.20);

            /* — Secondary: Amber — */
            --secondary:          #D97706;
            --secondary-soft:     rgba(217, 119, 6, 0.06);
            --secondary-muted:    rgba(217, 119, 6, 0.10);

            /* — Text — */
            --text-primary:       #171717;
            --text-secondary:     #525252;
            --text-tertiary:      #A3A3A3;
            --text-inverse:       #FAFAFA;
            --text-on-primary:    #FFFFFF;
            --text-sidebar:       #E4E4E7;
            --text-sidebar-secondary: #A1A1AA;
            --text-sidebar-muted: #52525B;

            /* — Semantic — */
            --success:            #16A34A;
            --success-muted:      rgba(22, 163, 74, 0.08);
            --danger:             #DC2626;
            --danger-muted:       rgba(220, 38, 38, 0.08);
            --warning:            #D97706;
            --warning-muted:      rgba(217, 119, 6, 0.08);
            --info:               #2563EB;
            --info-muted:         rgba(37, 99, 235, 0.08);

            /* — Borders — */
            --border-subtle:      #F0F0EE;
            --border-default:     #E5E5E3;
            --border-strong:      #D4D4D2;
            --border-focus:       var(--primary);

            /* — Radius (4px base) — */
            --radius-xs:   4px;
            --radius-sm:   6px;
            --radius-md:   8px;
            --radius-lg:   12px;
            --radius-xl:   16px;
            --radius-full: 9999px;

            /* — Shadows — */
            --shadow-xs:   0 1px 2px rgba(0,0,0,0.03);
            --shadow-sm:   0 1px 3px rgba(0,0,0,0.04), 0 1px 2px rgba(0,0,0,0.03);
            --shadow-md:   0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -2px rgba(0,0,0,0.03);
            --shadow-lg:   0 10px 15px -3px rgba(0,0,0,0.05), 0 4px 6px -4px rgba(0,0,0,0.03);
            --shadow-xl:   0 20px 25px -5px rgba(0,0,0,0.05), 0 8px 10px -6px rgba(0,0,0,0.03);
            --shadow-ring: 0 0 0 3px var(--primary-ring);

            /* — Spacing (4px base) — */
            --sp-1:  4px;   --sp-2:  8px;   --sp-3:  12px;
            --sp-4:  16px;  --sp-5:  20px;  --sp-6:  24px;
            --sp-8:  32px;  --sp-10: 40px;  --sp-12: 48px;
            --sp-16: 64px;  --sp-20: 80px;

            /* — Transitions — */
            --ease-out:        cubic-bezier(0.16, 1, 0.3, 1);
            --ease-spring:     cubic-bezier(0.34, 1.56, 0.64, 1);
            --duration-fast:   120ms;
            --duration-base:   150ms;
            --duration-slow:   300ms;
            --transition-colors: color var(--duration-base) var(--ease-out),
                                 background-color var(--duration-base) var(--ease-out),
                                 border-color var(--duration-base) var(--ease-out),
                                 box-shadow var(--duration-base) var(--ease-out);

            /* — Typography — */
            --font-display: 'Fraunces', Georgia, serif;
            --font-body:    'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            --text-xs:   0.75rem;    --leading-xs:   1rem;
            --text-sm:   0.8125rem;  --leading-sm:   1.25rem;
            --text-base: 0.875rem;   --leading-base: 1.375rem;
            --text-lg:   1rem;       --leading-lg:   1.5rem;
            --text-xl:   1.125rem;   --leading-xl:   1.5rem;
            --text-2xl:  1.5rem;     --leading-2xl:  2rem;
            --text-3xl:  1.875rem;   --leading-3xl:  2.375rem;
            --text-4xl:  2.25rem;    --leading-4xl:  2.75rem;
            --text-5xl:  3rem;       --leading-5xl:  1;
            --weight-light:    300;
            --weight-normal:   400;
            --weight-medium:   500;
            --weight-semibold: 600;
            --weight-bold:     700;

            /* — Accent aliases (architecture page) — */
            --gold:        #D97706;
            --gold-soft:   rgba(217, 119, 6, 0.08);
            --accent:      #6366F1;
            --accent-2:    #4F46E5;
            --accent-soft: rgba(99, 102, 241, 0.08);

            /* — Layout — */
            --sidebar-width:  256px;
            --topbar-height:  56px;
        }

        /* ═══════════════════════════════════════════════════════════
           RESET & BASE
           ═══════════════════════════════════════════════════════════ */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
        }

        body {
            font-family: var(--font-body);
            font-size: var(--text-base);
            line-height: var(--leading-base);
            font-weight: var(--weight-normal);
            color: var(--text-primary);
            background: var(--bg-page);
            background-image:
                radial-gradient(ellipse at 25% 0%, rgba(99,102,241,0.018) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 100%, rgba(217,119,6,0.012) 0%, transparent 60%);
            background-attachment: fixed;
            overflow-x: hidden;
        }

        ::selection { background: var(--primary-muted); color: var(--primary-hover); }
        a { color: inherit; text-decoration: none; }
        img { max-width: 100%; display: block; }
        button { font-family: inherit; cursor: pointer; }

        /* ═══════════════════════════════════════════════════════════
           TYPOGRAPHY
           ═══════════════════════════════════════════════════════════ */
        h1, h2, h3, h4 {
            font-family: var(--font-display);
            color: var(--text-primary);
            letter-spacing: -0.02em;
        }
        h1 {
            font-size: var(--text-3xl);
            line-height: var(--leading-3xl);
            font-weight: var(--weight-bold);
            margin-bottom: var(--sp-4);
        }
        h2 {
            font-size: var(--text-2xl);
            line-height: var(--leading-2xl);
            font-weight: var(--weight-semibold);
            margin-bottom: var(--sp-4);
        }
        h3 {
            font-size: var(--text-xl);
            line-height: var(--leading-xl);
            font-weight: var(--weight-semibold);
            margin-bottom: var(--sp-3);
        }
        h4 {
            font-size: var(--text-lg);
            line-height: var(--leading-lg);
            font-weight: var(--weight-medium);
            margin-bottom: var(--sp-2);
        }
        p {
            margin-bottom: var(--sp-4);
            color: var(--text-secondary);
        }
        strong { font-weight: var(--weight-semibold); color: var(--text-primary); }

        /* ═══════════════════════════════════════════════════════════
           LAYOUT — SIDEBAR
           ═══════════════════════════════════════════════════════════ */
        .sidebar {
            position: fixed;
            top: 0; left: 0; bottom: 0;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #17171A 0%, var(--bg-sidebar) 100%);
            border-right: 1px solid rgba(255,255,255,0.06);
            display: flex;
            flex-direction: column;
            z-index: 100;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar-brand {
            padding: var(--sp-6) var(--sp-5) var(--sp-5);
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        .sidebar-brand-link {
            display: flex;
            align-items: center;
            gap: var(--sp-3);
        }
        .sidebar-brand-icon {
            width: 32px; height: 32px;
            background: var(--primary);
            border-radius: var(--radius-md);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .sidebar-brand-icon svg { width: 16px; height: 16px; }
        .sidebar-brand-text {
            font-family: var(--font-display);
            font-style: italic;
            font-size: var(--text-xl);
            font-weight: var(--weight-medium);
            color: var(--text-sidebar);
            letter-spacing: -0.01em;
        }
        .sidebar-brand-sub {
            font-family: var(--font-body);
            font-size: 10px;
            color: var(--text-sidebar-muted);
            font-weight: var(--weight-medium);
            letter-spacing: 0.06em;
            text-transform: uppercase;
            margin-top: 1px;
        }

        .sidebar-nav {
            flex: 1;
            padding: var(--sp-4) var(--sp-3);
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .sidebar-section-label {
            font-size: 10px;
            font-weight: var(--weight-semibold);
            color: var(--text-sidebar-muted);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: var(--sp-4) var(--sp-3) var(--sp-2);
        }
        .nav-item {
            display: flex;
            align-items: center;
            gap: var(--sp-3);
            padding: var(--sp-2) var(--sp-3);
            border-radius: var(--radius-md);
            color: var(--text-sidebar-secondary);
            font-size: var(--text-sm);
            font-weight: var(--weight-medium);
            transition: var(--transition-colors);
            position: relative;
            cursor: pointer;
            height: 36px;
        }
        .nav-item:hover {
            color: var(--text-sidebar);
            background: var(--bg-sidebar-hover);
        }
        .nav-item.active {
            color: var(--text-sidebar);
            background: var(--bg-sidebar-active);
        }
        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0; top: 50%;
            transform: translateY(-50%);
            width: 2px; height: 20px;
            background: var(--primary);
            border-radius: 0 var(--radius-xs) var(--radius-xs) 0;
        }
        .nav-item svg {
            width: 16px; height: 16px;
            flex-shrink: 0;
            opacity: 0.5;
            transition: opacity var(--duration-base) var(--ease-out);
        }
        .nav-item:hover svg { opacity: 0.8; }
        .nav-item.active svg { opacity: 1; color: var(--primary); }

        .sidebar-user {
            padding: var(--sp-4) var(--sp-4) var(--sp-5);
            border-top: 1px solid rgba(255,255,255,0.06);
            display: flex;
            align-items: center;
            gap: var(--sp-3);
        }
        .sidebar-user-avatar {
            width: 32px; height: 32px;
            border-radius: var(--radius-full);
            background: linear-gradient(135deg, var(--primary), #818CF8);
            display: flex; align-items: center; justify-content: center;
            font-size: 11px;
            font-weight: var(--weight-bold);
            color: var(--text-on-primary);
            flex-shrink: 0;
        }
        .sidebar-user-info { flex: 1; min-width: 0; }
        .sidebar-user-name {
            font-size: var(--text-sm);
            font-weight: var(--weight-medium);
            color: var(--text-sidebar);
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .sidebar-user-role {
            font-size: 11px;
            color: var(--text-sidebar-muted);
        }

        /* ═══════════════════════════════════════════════════════════
           LAYOUT — TOPBAR
           ═══════════════════════════════════════════════════════════ */
        .topbar {
            position: sticky;
            top: 0;
            height: var(--topbar-height);
            margin-left: var(--sidebar-width);
            background: rgba(248, 248, 246, 0.82);
            -webkit-backdrop-filter: blur(16px) saturate(1.6);
            backdrop-filter: blur(16px) saturate(1.6);
            border-bottom: 1px solid var(--border-default);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 var(--sp-8);
            z-index: 90;
        }
        .topbar-context {
            font-size: var(--text-sm);
            font-weight: var(--weight-semibold);
            color: var(--text-primary);
        }
        .topbar-context span {
            color: var(--text-tertiary);
            font-weight: var(--weight-normal);
        }
        .topbar-actions {
            display: flex;
            align-items: center;
            gap: var(--sp-1);
        }

        /* ═══════════════════════════════════════════════════════════
           LAYOUT — CONTENT
           ═══════════════════════════════════════════════════════════ */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: var(--sp-8);
            min-height: calc(100vh - var(--topbar-height));
        }

        /* ═══════════════════════════════════════════════════════════
           BUTTONS
           ═══════════════════════════════════════════════════════════ */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: var(--sp-2);
            padding: var(--sp-2) var(--sp-4);
            font-family: var(--font-body);
            font-size: var(--text-sm);
            font-weight: var(--weight-semibold);
            line-height: 1;
            height: 36px;
            border: 1px solid transparent;
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: var(--transition-colors), transform var(--duration-fast) var(--ease-out);
            text-decoration: none;
            white-space: nowrap;
            user-select: none;
        }
        .btn:active { transform: scale(0.97); }
        .btn svg { width: 15px; height: 15px; }

        .btn,
        .btn-primary {
            background: var(--primary);
            color: var(--text-on-primary);
            box-shadow: var(--shadow-xs), 0 1px 0 0 rgba(255,255,255,0.1) inset;
        }
        .btn:hover,
        .btn-primary:hover {
            background: var(--primary-hover);
            box-shadow: var(--shadow-sm);
        }

        .btn-success {
            background: var(--success);
            color: var(--text-on-primary);
            box-shadow: var(--shadow-xs);
        }
        .btn-success:hover {
            background: #15803D;
            box-shadow: var(--shadow-sm);
        }

        .btn-ghost {
            background: transparent;
            color: var(--text-secondary);
            border-color: var(--border-default);
            box-shadow: none;
        }
        .btn-ghost:hover {
            background: var(--bg-muted);
            color: var(--text-primary);
            border-color: var(--border-strong);
        }

        .btn-danger {
            background: var(--danger);
            color: var(--text-on-primary);
        }
        .btn-danger:hover { background: #B91C1C; }

        .btn-icon {
            width: 36px; height: 36px;
            padding: 0;
            background: transparent;
            color: var(--text-tertiary);
            border-radius: var(--radius-md);
            border: 1px solid transparent;
            box-shadow: none;
        }
        .btn-icon:hover {
            background: var(--bg-muted);
            color: var(--text-secondary);
        }
        .btn-icon svg { width: 18px; height: 18px; }

        /* — Mode Badge — */
        .mode-badge {
            display: inline-flex;
            align-items: center;
            font-size: 10px;
            font-weight: var(--weight-semibold);
            letter-spacing: 0.04em;
            text-transform: uppercase;
            padding: 3px 8px;
            border-radius: var(--radius-full);
            line-height: 1;
        }
        .mode-badge.microservices {
            background: var(--primary-muted);
            color: var(--primary);
        }
        .mode-badge.monolithic {
            background: var(--secondary-muted);
            color: var(--secondary);
        }

        /* ═══════════════════════════════════════════════════════════
           CARDS
           ═══════════════════════════════════════════════════════════ */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border-default);
            border-radius: var(--radius-lg);
            padding: var(--sp-6);
            margin-bottom: var(--sp-6);
            box-shadow: var(--shadow-xs);
            transition: border-color var(--duration-base) var(--ease-out),
                        box-shadow var(--duration-base) var(--ease-out);
        }
        a.card:hover,
        .card-interactive:hover {
            border-color: var(--border-strong);
            box-shadow: var(--shadow-md);
        }
        .card-header {
            padding-bottom: var(--sp-4);
            margin-bottom: var(--sp-4);
            border-bottom: 1px solid var(--border-subtle);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* ═══════════════════════════════════════════════════════════
           TABLES
           ═══════════════════════════════════════════════════════════ */
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: var(--bg-card);
            border: 1px solid var(--border-default);
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-xs);
        }
        thead th {
            background: var(--bg-muted);
            padding: var(--sp-3) var(--sp-5);
            font-size: 11px;
            font-weight: var(--weight-semibold);
            color: var(--text-tertiary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            text-align: left;
            border-bottom: 1px solid var(--border-default);
        }
        tbody td {
            padding: var(--sp-4) var(--sp-5);
            font-size: var(--text-sm);
            color: var(--text-secondary);
            border-bottom: 1px solid var(--border-subtle);
            vertical-align: middle;
        }
        tbody tr {
            transition: background var(--duration-fast) var(--ease-out);
        }
        tbody tr:hover { background: var(--bg-muted); }
        tbody tr:last-child td { border-bottom: none; }
        tbody td:first-child {
            font-weight: var(--weight-medium);
            color: var(--text-primary);
        }

        /* ═══════════════════════════════════════════════════════════
           BADGES
           ═══════════════════════════════════════════════════════════ */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: var(--sp-1);
            padding: 2px var(--sp-2);
            font-size: 11px;
            font-weight: var(--weight-semibold);
            border-radius: var(--radius-full);
            line-height: 1.4;
        }
        .badge-primary {
            background: var(--primary-muted);
            color: var(--primary);
        }
        .badge-secondary {
            background: rgba(113, 113, 122, 0.08);
            color: #52525B;
        }
        .badge-success {
            background: var(--success-muted);
            color: var(--success);
        }
        .badge-danger {
            background: var(--danger-muted);
            color: var(--danger);
        }
        .badge-warning {
            background: var(--warning-muted);
            color: #92400E;
        }
        .badge-info {
            background: var(--info-muted);
            color: var(--info);
        }

        /* ═══════════════════════════════════════════════════════════
           FORM ELEMENTS
           ═══════════════════════════════════════════════════════════ */
        .form-group {
            margin-bottom: var(--sp-5);
        }
        label {
            display: block;
            font-size: var(--text-sm);
            font-weight: var(--weight-medium);
            color: var(--text-primary);
            margin-bottom: var(--sp-2);
            letter-spacing: -0.01em;
        }
        input, select, textarea {
            width: 100%;
            padding: var(--sp-2) var(--sp-3);
            background: var(--bg-card);
            border: 1px solid var(--border-default);
            border-radius: var(--radius-md);
            color: var(--text-primary);
            font-family: var(--font-body);
            font-size: var(--text-sm);
            line-height: var(--leading-sm);
            height: 36px;
            transition: var(--transition-colors);
            outline: none;
        }
        input::placeholder, textarea::placeholder {
            color: var(--text-tertiary);
        }
        input:hover, select:hover, textarea:hover {
            border-color: var(--border-strong);
        }
        input:focus, select:focus, textarea:focus {
            border-color: var(--primary);
            box-shadow: var(--shadow-ring);
            background: var(--bg-card);
        }
        select {
            -webkit-appearance: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23A3A3A3' viewBox='0 0 16 16'%3E%3Cpath d='M8 11.5l-5-5h10l-5 5z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right var(--sp-3) center;
            padding-right: var(--sp-10);
            cursor: pointer;
        }
        select option {
            background: var(--bg-card);
            color: var(--text-primary);
        }
        textarea {
            resize: vertical;
            min-height: 100px;
            height: auto;
        }

        /* ═══════════════════════════════════════════════════════════
           ALERTS & ERRORS
           ═══════════════════════════════════════════════════════════ */
        .alert-success {
            background: var(--success-muted);
            color: var(--success);
            padding: var(--sp-3) var(--sp-4);
            border-radius: var(--radius-md);
            border: 1px solid rgba(22, 163, 74, 0.12);
            margin-bottom: var(--sp-6);
            font-size: var(--text-sm);
            font-weight: var(--weight-medium);
        }
        .alert-danger {
            background: var(--danger-muted);
            color: var(--danger);
            padding: var(--sp-3) var(--sp-4);
            border-radius: var(--radius-md);
            border: 1px solid rgba(220, 38, 38, 0.12);
            margin-bottom: var(--sp-6);
            font-size: var(--text-sm);
        }
        .error-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: var(--sp-1);
        }
        .error-list li {
            font-size: var(--text-sm);
            color: var(--danger);
        }
        .error-list li::before {
            content: '\2022';
            margin-right: var(--sp-2);
            opacity: 0.5;
        }

        /* ═══════════════════════════════════════════════════════════
           AVATARS
           ═══════════════════════════════════════════════════════════ */
        .avatar {
            width: 32px; height: 32px;
            border-radius: var(--radius-full);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: var(--weight-bold);
            color: var(--text-on-primary);
            flex-shrink: 0;
        }
        .avatar-sm { width: 24px; height: 24px; font-size: 9px; }
        .avatar-lg { width: 44px; height: 44px; font-size: var(--text-base); }
        .avatar-xl { width: 56px; height: 56px; font-size: var(--text-lg); }
        .avatar-primary { background: linear-gradient(135deg, var(--primary), #818CF8); }
        .avatar-secondary { background: linear-gradient(135deg, #6B7280, #9CA3AF); }

        /* ═══════════════════════════════════════════════════════════
           STATS
           ═══════════════════════════════════════════════════════════ */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: var(--sp-4);
            margin-bottom: var(--sp-8);
        }
        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border-default);
            border-radius: var(--radius-lg);
            padding: var(--sp-5);
            box-shadow: var(--shadow-xs);
        }
        .stat { display: flex; flex-direction: column; gap: var(--sp-1); }
        .stat-value {
            font-family: var(--font-display);
            font-size: var(--text-3xl);
            font-weight: var(--weight-bold);
            color: var(--text-primary);
            line-height: 1;
            letter-spacing: -0.02em;
        }
        .stat-label {
            font-size: 11px;
            font-weight: var(--weight-medium);
            color: var(--text-tertiary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* ═══════════════════════════════════════════════════════════
           HERO
           ═══════════════════════════════════════════════════════════ */
        .hero {
            margin-bottom: var(--sp-8);
        }
        .hero-eyebrow {
            font-size: 11px;
            font-weight: var(--weight-semibold);
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: var(--sp-2);
        }
        .hero-title {
            font-family: var(--font-display);
            font-size: var(--text-3xl);
            line-height: var(--leading-3xl);
            font-weight: var(--weight-bold);
            color: var(--text-primary);
            letter-spacing: -0.02em;
            margin-bottom: var(--sp-1);
        }
        .hero-subtitle {
            font-size: var(--text-sm);
            color: var(--text-tertiary);
            margin-bottom: 0;
        }
        .hero-row {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: var(--sp-4);
            flex-wrap: wrap;
        }
        .hero-actions {
            display: flex;
            gap: var(--sp-3);
            align-items: center;
        }

        /* ═══════════════════════════════════════════════════════════
           BACK LINK
           ═══════════════════════════════════════════════════════════ */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: var(--sp-2);
            font-size: var(--text-sm);
            font-weight: var(--weight-medium);
            color: var(--text-tertiary);
            margin-bottom: var(--sp-6);
            transition: color var(--duration-base) var(--ease-out);
        }
        .back-link:hover { color: var(--text-primary); }
        .back-link svg { width: 14px; height: 14px; }

        /* ═══════════════════════════════════════════════════════════
           EMPTY STATE
           ═══════════════════════════════════════════════════════════ */
        .empty-state {
            text-align: center;
            padding: var(--sp-16) var(--sp-8);
        }
        .empty-state-icon {
            width: 48px; height: 48px;
            margin: 0 auto var(--sp-4);
            background: var(--bg-muted);
            border-radius: var(--radius-full);
            display: flex; align-items: center; justify-content: center;
            border: 1px solid var(--border-default);
        }
        .empty-state-icon svg { width: 20px; height: 20px; color: var(--text-tertiary); }
        .empty-state-title {
            font-family: var(--font-display);
            font-size: var(--text-lg);
            font-weight: var(--weight-semibold);
            color: var(--text-primary);
            margin-bottom: var(--sp-2);
        }
        .empty-state-text {
            font-size: var(--text-sm);
            color: var(--text-tertiary);
            margin-bottom: var(--sp-6);
            max-width: 320px;
            margin-left: auto;
            margin-right: auto;
        }

        /* ═══════════════════════════════════════════════════════════
           DIVIDER
           ═══════════════════════════════════════════════════════════ */
        .divider, hr {
            border: none;
            height: 1px;
            background: var(--border-default);
            margin: var(--sp-6) 0;
        }

        /* ═══════════════════════════════════════════════════════════
           TOAST NOTIFICATION
           ═══════════════════════════════════════════════════════════ */
        .toast-container {
            position: fixed;
            bottom: var(--sp-6);
            right: var(--sp-6);
            z-index: 9999;
            display: flex;
            flex-direction: column-reverse;
            gap: var(--sp-3);
            pointer-events: none;
        }
        .toast {
            pointer-events: auto;
            display: flex;
            align-items: center;
            gap: var(--sp-3);
            padding: var(--sp-3) var(--sp-4);
            background: var(--bg-card);
            border: 1px solid var(--border-default);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            font-size: var(--text-sm);
            font-weight: var(--weight-medium);
            color: var(--text-primary);
            max-width: 380px;
            position: relative;
            overflow: hidden;
            animation: toastSlideUp 0.4s var(--ease-spring) forwards;
        }
        .toast.toast-dismissing {
            animation: toastSlideDown 0.3s var(--ease-out) forwards;
        }
        .toast-icon {
            width: 20px; height: 20px;
            border-radius: var(--radius-full);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .toast-success .toast-icon {
            background: var(--success-muted);
            color: var(--success);
        }
        .toast-close {
            margin-left: auto;
            background: none; border: none;
            color: var(--text-tertiary);
            cursor: pointer;
            padding: var(--sp-1);
            border-radius: var(--radius-xs);
            display: flex; align-items: center; justify-content: center;
            transition: color var(--duration-fast) var(--ease-out);
        }
        .toast-close:hover { color: var(--text-primary); }
        .toast-close svg { width: 14px; height: 14px; }
        .toast-progress {
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 2px;
            background: var(--success);
            border-radius: 0 0 var(--radius-lg) var(--radius-lg);
            transform-origin: left;
            animation: toastProgress 4s linear forwards;
        }

        @keyframes toastSlideUp {
            0%   { opacity: 0; transform: translateY(12px) scale(0.96); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }
        @keyframes toastSlideDown {
            0%   { opacity: 1; transform: translateY(0) scale(1); }
            100% { opacity: 0; transform: translateY(8px) scale(0.96); }
        }
        @keyframes toastProgress {
            0%   { transform: scaleX(1); }
            100% { transform: scaleX(0); }
        }

        /* ═══════════════════════════════════════════════════════════
           ANIMATIONS
           ═══════════════════════════════════════════════════════════ */
        @keyframes pageReveal {
            0%   { opacity: 0; transform: translateY(8px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .main-content > * {
            animation: pageReveal 0.45s var(--ease-out) backwards;
        }
        .main-content > *:nth-child(1) { animation-delay: 0.04s; }
        .main-content > *:nth-child(2) { animation-delay: 0.10s; }
        .main-content > *:nth-child(3) { animation-delay: 0.16s; }
        .main-content > *:nth-child(4) { animation-delay: 0.22s; }
        .main-content > *:nth-child(5) { animation-delay: 0.28s; }
        .main-content > *:nth-child(6) { animation-delay: 0.34s; }
        .main-content > *:nth-child(7) { animation-delay: 0.38s; }
        .main-content > *:nth-child(8) { animation-delay: 0.42s; }

        /* ═══════════════════════════════════════════════════════════
           SCROLLBAR
           ═══════════════════════════════════════════════════════════ */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb {
            background: var(--border-strong);
            border-radius: var(--radius-full);
        }
        ::-webkit-scrollbar-thumb:hover { background: var(--text-tertiary); }

        /* ═══════════════════════════════════════════════════════════
           RESPONSIVE
           ═══════════════════════════════════════════════════════════ */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform var(--duration-slow) var(--ease-out);
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .topbar,
            .main-content {
                margin-left: 0;
            }
            .main-content {
                padding: var(--sp-4);
            }
        }
    </style>
</head>
<body>

    {{-- ═══════ SIDEBAR ═══════ --}}
    <aside class="sidebar">
        <div class="sidebar-brand">
            <a href="/" class="sidebar-brand-link">
                <div class="sidebar-brand-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 6s1.5-2 5-2 5 2 5 2v14s-1.5-1-5-1-5 1-5 1V6z"/>
                        <path d="M12 6s1.5-2 5-2 5 2 5 2v14s-1.5-1-5-1-5 1-5 1V6z"/>
                    </svg>
                </div>
                <div>
                    <div class="sidebar-brand-text">Academe</div>
                    <div class="sidebar-brand-sub">Course System</div>
                </div>
            </a>
        </div>

        <nav class="sidebar-nav">
            <div class="sidebar-section-label">Platform</div>

            <a href="{{ route('courses.index') }}"
               class="nav-item {{ request()->routeIs('courses.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="9" rx="1"/>
                    <rect x="14" y="3" width="7" height="5" rx="1"/>
                    <rect x="14" y="12" width="7" height="9" rx="1"/>
                    <rect x="3" y="16" width="7" height="5" rx="1"/>
                </svg>
                Courses
            </a>

            <a href="{{ route('students.index') }}"
               class="nav-item {{ request()->routeIs('students.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M2 21v-2a4 4 0 014-4h6a4 4 0 014 4v2"/>
                    <path d="M16 3.13a4 4 0 010 7.75"/>
                    <path d="M21 21v-2a4 4 0 00-3-3.87"/>
                </svg>
                Students
            </a>

            <a href="{{ route('enrollments.index') }}"
               class="nav-item {{ request()->routeIs('enrollments.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 4h2a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2h2"/>
                    <rect x="8" y="2" width="8" height="4" rx="1"/>
                    <path d="M9 14l2 2 4-4"/>
                </svg>
                Enrollments
            </a>

            <div class="sidebar-section-label" style="margin-top: var(--sp-4);">System</div>

            <a href="{{ route('architecture') }}"
               class="nav-item {{ request()->routeIs('architecture') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="3"/>
                    <path d="M12 2v4m0 12v4m-7.07-15.07l2.83 2.83m8.48 8.48l2.83 2.83M2 12h4m12 0h4M4.93 19.07l2.83-2.83m8.48-8.48l2.83-2.83"/>
                </svg>
                Architecture
            </a>
        </nav>

        <div class="sidebar-user">
            <div class="sidebar-user-avatar">AD</div>
            <div class="sidebar-user-info">
                <div class="sidebar-user-name">Admin User</div>
                <div class="sidebar-user-role">Administrator</div>
            </div>
        </div>
    </aside>

    {{-- ═══════ TOPBAR ═══════ --}}
    <header class="topbar">
        <div class="topbar-context">
            @yield('title', 'Dashboard')
            @hasSection('subtitle')
                <span>&nbsp;/&nbsp;@yield('subtitle')</span>
            @endif
        </div>
        <div class="topbar-actions">
            @if(config('backend.mode') === 'microservices')
                <span class="mode-badge microservices">Microservices</span>
            @else
                <span class="mode-badge monolithic">Monolithic</span>
            @endif
            <button class="btn-icon" title="Search">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                </svg>
            </button>
            <button class="btn-icon" title="Notifications">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                    <path d="M13.73 21a2 2 0 01-3.46 0"/>
                </svg>
            </button>
        </div>
    </header>

    {{-- ═══════ CONTENT ═══════ --}}
    <main class="main-content">
        @yield('content')
    </main>

    {{-- ═══════ TOAST ═══════ --}}
    <div class="toast-container" id="toastContainer">
        @if(session('success'))
            <div class="toast toast-success" data-auto-dismiss="4000">
                <div class="toast-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:12px;height:12px;">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                </div>
                <span>{{ session('success') }}</span>
                <button class="toast-close" onclick="dismissToast(this.closest('.toast'))">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
                <div class="toast-progress"></div>
            </div>
        @endif
    </div>

    <script>
        function dismissToast(el) {
            if (!el || el.classList.contains('toast-dismissing')) return;
            el.classList.add('toast-dismissing');
            el.addEventListener('animationend', function() { el.remove(); }, { once: true });
        }
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.toast[data-auto-dismiss]').forEach(function(toast) {
                var delay = parseInt(toast.getAttribute('data-auto-dismiss'), 10) || 4000;
                setTimeout(function() { dismissToast(toast); }, delay);
            });
        });
    </script>
    <script src="/js/academe.js"></script>
</body>
</html>
