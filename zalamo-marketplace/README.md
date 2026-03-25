# Zalamo Marketplace (MVP)

To starter plugin do WordPressa dla marketplace'u modowego inspirowanego serwisami typu Zalando.

## Co zawiera

- Custom Post Type `zalamo_product` (produkty).
- Taxonomia `zalamo_brand` (marki).
- Rola użytkownika `zalamo_vendor` (sprzedawca).
- Pola profilu sprzedawcy: nazwa i opis sklepu.
- Shortcode storefrontu: `[zalamo_storefront per_page="12" brand="nike"]`
- Shortcode panelu sprzedawcy: `[zalamo_vendor_dashboard]`
- Zakładki w `wp-admin`: **Panel fotografa** i **Panel klienta** w menu `Zalamo Marketplace`.

## Instalacja

1. Skopiuj katalog `zalamo-marketplace` do `wp-content/plugins/`.
2. Aktywuj plugin w panelu WordPress.
3. Utwórz użytkownika z rolą `Sprzedawca Marketplace`.
4. Dodaj stronę z shortcode `[zalamo_storefront]`.
5. Dodaj stronę z shortcode `[zalamo_vendor_dashboard]`.

## Dalszy rozwój (roadmap)

- Integracja z WooCommerce (koszyk, checkout, płatności).
- Moderacja ofert i SLA sprzedawców.
- Prowizje marketplace i payouty.
- Rozliczenia VAT + faktury.
- Integracje kurierskie i tracking przesyłek.
