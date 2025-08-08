# Admin Panel Styles Documentation

## Overview
Style CSS custom untuk admin panel Kadar Rent Car yang modern dan responsif.

## Class CSS yang Tersedia

### 1. Layout Components

#### Sidebar
- `.admin-sidebar` - Container sidebar dengan gradient biru
- `.nav-link` - Link navigasi dengan hover effects
- `.nav-link.active` - Link aktif dengan background biru

#### Header
- `.admin-header` - Header dengan shadow dan border
- `.user-info` - Container info user
- `.user-avatar` - Avatar user dengan background biru

### 2. Dashboard Components

#### Stat Cards
- `.stat-card` - Card statistik dengan hover effects
- `.stat-card.blue` - Card dengan tema biru
- `.stat-card.green` - Card dengan tema hijau
- `.stat-card.yellow` - Card dengan tema kuning
- `.stat-card.purple` - Card dengan tema ungu
- `.icon-container` - Container icon dalam stat card
- `.stat-number` - Angka statistik
- `.stat-label` - Label statistik

#### Action Buttons
- `.action-btn` - Base button style
- `.action-btn.primary` - Button primary (biru)
- `.action-btn.success` - Button success (hijau)
- `.action-btn.warning` - Button warning (kuning)
- `.action-btn.danger` - Button danger (merah)

### 3. Table Components

#### Admin Table
- `.admin-table` - Table dengan rounded corners dan shadow
- `.admin-table thead` - Header table
- `.admin-table th` - Header cell
- `.admin-table td` - Data cell
- `.admin-table tbody tr` - Table row dengan hover effect

#### Modern Table
- `.table-modern` - Table dengan gradient header
- `.table-modern thead` - Header dengan gradient
- `.table-modern th` - Header cell modern
- `.table-modern td` - Data cell modern

### 4. Form Components

#### Admin Form
- `.admin-form` - Container form dengan shadow
- `.form-group` - Group form elements
- `.admin-form label` - Label form
- `.admin-form input` - Input field
- `.admin-form select` - Select field
- `.admin-form textarea` - Textarea field

#### Modern Form
- `.form-modern` - Form dengan padding besar dan shadow
- `.form-modern .form-group` - Group form modern
- `.form-modern label` - Label modern
- `.form-modern input` - Input modern
- `.form-modern select` - Select modern
- `.form-modern textarea` - Textarea modern

### 5. Status & Badge Components

#### Status Badges
- `.status-badge` - Base status badge
- `.status-badge.active` - Status aktif (hijau)
- `.status-badge.inactive` - Status tidak aktif (merah)
- `.status-badge.pending` - Status pending (kuning)
- `.status-badge.completed` - Status selesai (biru)

#### Modern Badges
- `.badge-modern` - Badge modern dengan rounded corners
- `.badge-modern.success` - Badge success
- `.badge-modern.danger` - Badge danger
- `.badge-modern.warning` - Badge warning
- `.badge-modern.info` - Badge info

### 6. Alert Components

#### Admin Alerts
- `.admin-alert` - Base alert
- `.admin-alert.success` - Alert success
- `.admin-alert.error` - Alert error
- `.admin-alert.warning` - Alert warning
- `.admin-alert.info` - Alert info

#### Modern Alerts
- `.alert-modern` - Alert modern dengan border-left
- `.alert-modern.success` - Alert success modern
- `.alert-modern.error` - Alert error modern
- `.alert-modern.warning` - Alert warning modern
- `.alert-modern.info` - Alert info modern

### 7. Modal Components

#### Admin Modal
- `.admin-modal` - Container modal
- `.admin-modal .modal-overlay` - Overlay modal
- `.admin-modal .modal-content` - Content modal

#### Modern Modal
- `.modal-modern` - Modal modern dengan backdrop blur
- `.modal-content` - Content modal modern

### 8. Loading & Animation Components

#### Loading Spinner
- `.loading-spinner` - Spinner loading sederhana
- `.spinner-modern` - Spinner modern dengan animasi

#### Animations
- `.fade-in-up` - Animasi fade in dari bawah
- `.hover-lift` - Efek hover lift
- `.card-hover` - Hover effect untuk card

### 9. Navigation Components

#### Modern Navigation
- `.nav-modern` - Navigation modern dengan gradient
- `.nav-modern .nav-item` - Navigation item
- `.nav-modern .nav-item:hover` - Hover state
- `.nav-modern .nav-item.active` - Active state

### 10. Utility Components

#### Scrollbar
- `.custom-scrollbar` - Custom scrollbar styling

#### Gradient Buttons
- `.btn-gradient` - Button dengan gradient background

#### Modern Stats
- `.stats-modern` - Container stats modern
- `.stat-card-modern` - Card statistik modern
- `.stat-card-modern .stat-icon` - Icon statistik
- `.stat-card-modern .stat-value` - Value statistik
- `.stat-card-modern .stat-label` - Label statistik

## Responsive Design

Semua komponen sudah responsive dengan breakpoint:
- Mobile: < 768px
- Tablet: 768px - 1024px
- Desktop: > 1024px

## Dark Mode Support

Semua komponen mendukung dark mode dengan `@media (prefers-color-scheme: dark)`.

## Usage Examples

### Dashboard Card
```html
<div class="stat-card blue hover-lift">
    <div class="flex items-center">
        <div class="icon-container">
            <i class="fas fa-users"></i>
        </div>
        <div class="ml-4">
            <p class="stat-label">Total Users</p>
            <p class="stat-number">1,234</p>
        </div>
    </div>
</div>
```

### Action Button
```html
<button class="action-btn primary">
    <i class="fas fa-plus mr-2"></i>Tambah Data
</button>
```

### Form
```html
<div class="admin-form">
    <div class="form-group">
        <label for="name">Nama</label>
        <input type="text" id="name" name="name" required>
    </div>
</div>
```

### Table
```html
<table class="admin-table">
    <thead>
        <tr>
            <th>Nama</th>
            <th>Email</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>John Doe</td>
            <td>john@example.com</td>
        </tr>
    </tbody>
</table>
```

### Alert
```html
<div class="admin-alert success">
    <i class="fas fa-check-circle"></i>
    <span>Data berhasil disimpan!</span>
</div>
```

## File Structure

```
resources/
├── css/
│   └── app.css          # Main CSS file dengan semua styles
└── views/
    └── admin/
        ├── layouts/
        │   └── app.blade.php    # Layout utama admin
        ├── dashboard.blade.php   # Dashboard
        ├── cars/
        │   ├── index.blade.php   # List cars
        │   └── create.blade.php  # Create car
        └── ...
```

## Build Process

Untuk mengkompilasi CSS:
```bash
npm run build
```

File CSS akan di-generate di `public/build/assets/` dengan nama yang unik. 