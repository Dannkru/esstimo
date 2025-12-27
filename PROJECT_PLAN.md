# Plan Budowy Aplikacji Estimo - Kalkulator Wycen Budowlanych

## Technologie
- **Backend:** Laravel 12 + Livewire 3
- **Frontend:** Tailwind CSS v4 (już zainstalowany)
- **Dodatkowo:** Alpine.js (dla dodatkowych interakcji)

## Architektura

### Modele danych:
1. **Category** - kategorie prac (malarskie, glazurnicze, etc.)
2. **Service** - poszczególne usługi w kategoriach (szpachlowanie, malowanie, etc.)
3. **Estimate** - wyceny użytkowników (tylko dla zalogowanych)
4. **EstimateItem** - pozycje w wycenie
5. **User** - użytkownicy (z subskrypcją)
6. **Subscription** - subskrypcje użytkowników

### Funkcjonalności:
- **Dla niezalogowanych:** Kalkulator z możliwością druku (bez zapisu)
- **Dla zalogowanych:** Zapisywanie wycen + abonament 10 zł/mies

---

## ETAPY BUDOWY

### ✅ ETAP 1: Frontend - Layout i Landing Page
**Cel:** Piękna, przejrzysta strona główna z kafelkami kategorii

**Zadania:**
- [x] Instalacja Tailwind CSS v4
- [ ] Stworzenie głównego layoutu
- [ ] Landing page z kafelkami kategorii
- [ ] Responsywny design
- [ ] Animacje i przejścia

**Commit:** "feat: Landing page with category tiles"

---

### ETAP 2: Frontend - Arkusz Obliczeniowy
**Cel:** Interaktywny formularz z checkboxami i kalkulacją

**Zadania:**
- [ ] Komponent Livewire dla arkusza
- [ ] Checkboxy dla usług
- [ ] Pola ilości (m²) i ceny
- [ ] Automatyczne obliczenia
- [ ] Podsumowanie wyceny
- [ ] Przycisk druku

**Commit:** "feat: Calculator sheet with dynamic calculations"

---

### ETAP 3: Backend - Modele i Migracje
**Cel:** Struktura bazy danych

**Zadania:**
- [ ] Migracja categories
- [ ] Migracja services
- [ ] Migracja estimates
- [ ] Migracja estimate_items
- [ ] Migracja subscriptions
- [ ] Modele Eloquent
- [ ] Seedery z przykładowymi danymi

**Commit:** "feat: Database models and migrations"

---

### ETAP 4: Backend - Logika Kalkulacji
**Cel:** Połączenie frontendu z backendem

**Zadania:**
- [ ] Livewire component dla kalkulatora
- [ ] Pobieranie usług z bazy
- [ ] Logika obliczeń
- [ ] Walidacja danych
- [ ] Obsługa sesji dla niezalogowanych

**Commit:** "feat: Calculator logic and Livewire integration"

---

### ETAP 5: System Autoryzacji
**Cel:** Logowanie i rejestracja

**Zadania:**
- [ ] Laravel Breeze/Jetstream (lub własny)
- [ ] Formularze logowania/rejestracji
- [ ] Middleware dla chronionych tras
- [ ] Dashboard użytkownika

**Commit:** "feat: User authentication system"

---

### ETAP 6: Funkcjonalności Premium
**Cel:** Zapisywanie wycen i płatności

**Zadania:**
- [ ] System zapisywania wycen
- [ ] Lista zapisanych wycen
- [ ] Edycja/usuwanie wycen
- [ ] Integracja płatności (Stripe/PayU)
- [ ] System subskrypcji
- [ ] Panel zarządzania subskrypcją

**Commit:** "feat: Premium features - save estimates and subscriptions"

---

## Dodatkowe Usprawnienia (opcjonalnie)
- Eksport do PDF
- Współdzielenie wycen
- Szablony wycen
- Historia zmian
- Powiadomienia email

