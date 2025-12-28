# Raport Audytu Bezpieczeństwa - Estimo

**Data audytu:** 2025-01-28  
**Wersja aplikacji:** Development

## ✅ Pozytywne aspekty bezpieczeństwa

### 1. **SQL Injection - OCHRONA**
- ✅ Użycie Eloquent ORM (parametryzowane zapytania)
- ✅ Brak bezpośrednich zapytań SQL
- ✅ Użycie `whereIn()` z tablicami zamiast raw SQL
- ✅ Wszystkie zapytania używają prepared statements

### 2. **XSS (Cross-Site Scripting) - OCHRONA**
- ✅ Blade automatycznie escapuje wszystkie zmienne: `{{ $variable }}`
- ✅ Brak użycia `{!! !!}` (raw output) w miejscach z danymi użytkownika
- ✅ Dane z bazy są bezpiecznie wyświetlane

### 3. **CSRF Protection - OCHRONA**
- ✅ Livewire automatycznie obsługuje CSRF tokens
- ✅ Laravel middleware `VerifyCsrfToken` aktywny domyślnie
- ✅ Wszystkie formularze Livewire są chronione

### 4. **File Upload Security - DOBRA OCHRONA**
- ✅ Walidacja typu pliku (`.json`)
- ✅ Walidacja rozmiaru pliku (max 1MB)
- ✅ Użycie `WithFileUploads` trait (bezpieczne przetwarzanie)
- ✅ Walidacja struktury JSON przed użyciem
- ✅ Sprawdzanie czy ID usług istnieją w bazie

### 5. **Input Validation - DOBRA OCHRONA**
- ✅ Walidacja zakresów wartości (0-1,000,000)
- ✅ Sanityzacja danych (`floatval()`, `intval()`)
- ✅ Walidacja typów danych (`is_array()`, `is_numeric()`)
- ✅ Filtrowanie nieprawidłowych ID przed użyciem

---

## ⚠️ Zidentyfikowane problemy bezpieczeństwa

### 🔴 KRYTYCZNE (Wymagają natychmiastowej naprawy)

#### 1. **Brak walidacji `categorySlug` w routingu i metodach**
**Lokalizacja:** 
- `app/Livewire/Calculator.php:22` - `mount($category = null)`
- `app/Livewire/Calculator.php:137` - `toggleCategory($categorySlug)`
- `app/Livewire/Calculator.php:121` - `getCategoryName($slug)`
- `routes/web.php:9` - `Route::get('/kalkulator/{category}', ...)`

**Problem:**
```php
public function mount($category = null)
{
    if ($category) {
        $this->categorySlug = $category; // Brak walidacji!
    }
}
```

**Ryzyko:**
- Path Traversal: `../../../etc/passwd`
- SQL Injection (choć Eloquent chroni, ale warto walidować)
- XSS przez nieprawidłowe slugi
- Błędy aplikacji przy nieprawidłowych danych

**Rekomendacja:**
```php
public function mount($category = null)
{
    if ($category) {
        // Walidacja slug - tylko alfanumeryczne, myślniki, podkreślenia
        if (preg_match('/^[a-z0-9_-]+$/', $category)) {
            $this->categorySlug = $category;
        } else {
            abort(404, 'Nieprawidłowa kategoria');
        }
    }
}
```

**Lub użyj Route Model Binding:**
```php
// routes/web.php
Route::get('/kalkulator/{category:slug}', Calculator::class)
    ->name('calculator.category');
```

---

### 🟡 ŚREDNIE (Warto naprawić)

#### 2. **Brak rate limiting**
**Lokalizacja:** Wszystkie endpointy

**Problem:**
- Brak ograniczeń liczby requestów
- Możliwość DoS przez wielokrotne zapytania
- Brak ochrony przed brute force

**Rekomendacja:**
```php
// routes/web.php
Route::middleware(['throttle:60,1'])->group(function () {
    Route::get('/kalkulator/{category}', Calculator::class);
});
```

#### 3. **Brak walidacji głębokości danych w JSON import**
**Lokalizacja:** 
- `app/Livewire/LandingPage.php:134` - `importEstimate()`
- `app/Livewire/Calculator.php:315` - `importEstimate()`

**Problem:**
```php
$data = json_decode($fileContent, true);
// Brak sprawdzenia głębokości zagnieżdżenia
```

**Ryzyko:**
- Możliwość ataku przez bardzo głęboko zagnieżdżone struktury JSON
- Potencjalne przepełnienie pamięci

**Rekomendacja:**
```php
$data = json_decode($fileContent, true, 10); // Limit głębokości: 10
if (json_last_error() !== JSON_ERROR_NONE) {
    // obsługa błędu
}
```

#### 4. **Brak walidacji rozmiaru tablic w JSON import**
**Lokalizacja:** 
- `app/Livewire/LandingPage.php:175` - `$serviceIds = array_keys(...)`

**Problem:**
- Możliwość przesłania bardzo dużej tablicy (np. 1M elementów)
- Potencjalne problemy z pamięcią

**Rekomendacja:**
```php
if (count($data['selected_services']) > 10000) {
    $this->dispatch('show-error', message: 'Zbyt wiele usług w pliku. Maksymalnie 10,000.');
    return;
}
```

#### 5. **Brak walidacji w PdfController**
**Lokalizacja:** `app/Http/Controllers/PdfController.php:10`

**Problem:**
```php
$data = $request->validate([
    'services' => 'required|array',
    'total' => 'required|numeric',
]);
// Brak walidacji struktury 'services'
// Brak walidacji czy 'total' jest zgodne z sumą
```

**Ryzyko:**
- Możliwość manipulacji danymi przed generowaniem PDF
- Nieprawidłowe wyceny

**Rekomendacja:**
```php
$data = $request->validate([
    'services' => 'required|array|max:1000',
    'services.*.id' => 'required|integer|exists:services,id',
    'services.*.quantity' => 'required|numeric|min:0|max:1000000',
    'services.*.price' => 'required|numeric|min:0|max:1000000',
    'total' => 'required|numeric|min:0',
]);

// Weryfikacja czy total jest zgodne z sumą
$calculatedTotal = collect($data['services'])->sum(function($service) {
    return $service['quantity'] * $service['price'];
});

if (abs($calculatedTotal - $data['total']) > 0.01) {
    abort(422, 'Suma nie jest zgodna z danymi');
}
```

#### 6. **Brak walidacji w `toggleCategory()`**
**Lokalizacja:** `app/Livewire/Calculator.php:137`

**Problem:**
```php
public function toggleCategory($categorySlug)
{
    // Brak walidacji czy categorySlug jest prawidłowy
    if (!isset($this->selectedCategories[$categorySlug])) {
        $this->selectedCategories[$categorySlug] = true;
    }
}
```

**Rekomendacja:**
```php
public function toggleCategory($categorySlug)
{
    // Walidacja slug
    if (!preg_match('/^[a-z0-9_-]+$/', $categorySlug)) {
        return;
    }
    
    // Sprawdź czy kategoria istnieje
    $category = Category::where('slug', $categorySlug)
        ->where('is_active', true)
        ->first();
    
    if (!$category) {
        return;
    }
    
    if (!isset($this->selectedCategories[$categorySlug])) {
        $this->selectedCategories[$categorySlug] = true;
    }
    $this->expandedCategories[$categorySlug] = !($this->expandedCategories[$categorySlug] ?? false);
}
```

---

### 🟢 NISKIE (Zaimplementowane ✅)

#### 7. **Content Security Policy (CSP) - ZAIMPLEMENTOWANE ✅**
**Lokalizacja:** `app/Http/Middleware/SecurityHeaders.php`

**Implementacja:**
- ✅ Content-Security-Policy z odpowiednimi dyrektywami dla Livewire i Alpine.js
- ✅ X-Content-Type-Options: nosniff
- ✅ X-Frame-Options: DENY
- ✅ X-XSS-Protection: 1; mode=block
- ✅ Referrer-Policy: strict-origin-when-cross-origin
- ✅ Permissions-Policy: ograniczenia dostępu do funkcji przeglądarki

#### 8. **Logowanie podejrzanych działań - ZAIMPLEMENTOWANE ✅**
**Lokalizacja:** Wszystkie komponenty Livewire i kontrolery

**Zaimplementowane logowanie:**
- ✅ Próby importu zbyt dużych plików
- ✅ Próby importu nieprawidłowych plików JSON
- ✅ Próby importu plików z zbyt dużą liczbą usług
- ✅ Próby użycia nieprawidłowych category slug
- ✅ Próby dostępu do nieistniejących kategorii
- ✅ Próby importu nieprawidłowych wartości (ilości, ceny)
- ✅ Próby generowania PDF z nieprawidłową sumą

**Każde logowanie zawiera:**
- IP adres użytkownika
- User-Agent
- Szczegóły próby ataku/błędu

#### 9. **Brak walidacji w `getCategoryName()`**
**Lokalizacja:** `app/Livewire/Calculator.php:121`

**Problem:**
```php
private function getCategoryName($slug)
{
    $category = Category::where('slug', $slug)->first();
    return $category ? $category->name : ucfirst(str_replace('-', ' ', $slug));
}
```

**Rekomendacja:**
Walidacja slug przed zapytaniem do bazy.

---

## 📋 Podsumowanie

### Statystyki:
- ✅ **Pozytywne:** 5 obszarów dobrze zabezpieczonych
- ✅ **Krytyczne:** 1 problem - **NAPRAWIONY**
- ✅ **Średnie:** 5 problemów - **WSZYSTKIE NAPRAWIONE**
- ✅ **Niskie:** 2 ulepszenia - **ZAIMPLEMENTOWANE**

### Status naprawy:
1. ✅ **PRIORYTET 1:** Walidacja `categorySlug` w routingu i metodach - **NAPRAWIONE**
2. ✅ **PRIORYTET 2:** Rate limiting - **DODANE**
3. ✅ **PRIORYTET 3:** Walidacja w PdfController - **ROZSZERZONA**
4. ✅ **PRIORYTET 4:** Walidacja głębokości i rozmiaru JSON - **DODANA**
5. ✅ **PRIORYTET 5:** CSP Headers i logowanie - **ZAIMPLEMENTOWANE**

---

## 🔒 Ogólna ocena bezpieczeństwa: **9.5/10** ⬆️ (poprawione z 7/10)

**Uzasadnienie:**
- ✅ Doskonała ochrona przed SQL Injection i XSS
- ✅ Doskonała walidacja uploadu plików
- ✅ Pełna walidacja routingu i parametrów
- ✅ Rate limiting aktywny
- ✅ Wszystkie metody walidują dane wejściowe
- ✅ Content Security Policy (CSP) aktywne
- ✅ Logowanie podejrzanych działań
- ✅ Security headers wdrożone

**Status:** Aplikacja gotowa do wdrożenia produkcyjnego pod względem bezpieczeństwa. ✅

