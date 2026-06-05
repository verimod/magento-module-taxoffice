# Verimod_TaxOffice Module for Magento 2.4.9

## Açıklama

Bu modül Magento 2 Commerce/Community 2.4.9 sürümü için "Vergi Müşterisisi Kimlik Numarası" (Tax Office Number) adres alanı ekler. Alan yalnızca **Fatura Adresleri (Billing Address)** için gösterilir ve **Teslimat Adresleri (Shipping Address)** da görünmez.

## Özellikler

✅ Fatura adreslerine özel "Vergi Müşterisisi Kimlik Numarası" alanı  
✅ Teslimat adreslerinden alan otomatik olarak gizlenir  
✅ İlk adres ekleme popup senaryosu desteklenir (Billing olarak kabul)  
✅ Müşteri hesabı adres yönetimi sayfasında desteklenir  
✅ Admin panelinde adres yönetimi desteği  
✅ Checkout sayfasında tam entegrasyon  
✅ Veritabanı otomatik migrasyon (db_schema.xml)

## Gereksinimler

- Magento 2.4.9
- PHP 7.4+
- MySQL 5.7+

## Kurulum

### 1. Modülü İndirin

```bash
cd /path/to/magento
git clone https://github.com/verimod/magento-module-taxoffice.git app/code/Verimod/TaxOffice
```

### 2. Modülü Etkinleştirin

```bash
cd /path/to/magento
php bin/magento module:enable Verimod_TaxOffice
```

### 3. Setup'ı Çalıştırın

```bash
php bin/magento setup:upgrade
```

### 4. Statik Dosyaları Derin Temizle

```bash
rm -rf var/cache/*
rm -rf var/page_cache/*
rm -rf var/view_preprocessed/*
php bin/magento setup:static-content:deploy -f
```

### 5. Cache'i Temizleyin

```bash
php bin/magento cache:clean
php bin/magento cache:flush
```

## Modül Yapısı

```
Verimod/TaxOffice/
├── registration.php                      # Modül kaydı
├── etc/
│   ├── module.xml                        # Modül konfigürasyonu
│   ├── di.xml                            # Bağımlılık enjeksiyonu
│   ├── config.xml                        # Varsayılan konfigürasyon
│   ├── customer_attributes.xml           # Öznitelik tanımı
│   └── db_schema.xml                     # Veritabanı şeması
├── Plugin/
│   ├── CheckoutLayoutProcessorPlugin.php # Checkout layout işleme
│   ├── AddressRepositoryPlugin.php       # Adres kayıt işleme
│   └── AddressModelPlugin.php            # Adres model işleme
└── README.md                             # Bu dosya
```

## Konfigürasyon

### Varsayılan Ayarlar (etc/config.xml)

```xml
<verimod_taxoffice>
    <general>
        <enabled>1</enabled>                              <!-- Modül aktif/pasif -->
        <tax_office_attribute_label>Vergi Müşterisisi Kimlik Numarası</tax_office_attribute_label>
        <show_in_billing_address>1</show_in_billing_address>    <!-- Fatura adresinde göster -->
        <show_in_shipping_address>0</show_in_shipping_address>  <!-- Teslimat adresinde gizle -->
        <is_required>0</is_required>                    <!-- Alan opsiyonel -->
    </general>
</verimod_taxoffice>
```

## Senaryolar

### Senaryo 1: Yeni Müşteri İlk Adres Ekleme

1. Müşteri kayıt olup sisteme giriş yapar
2. "Adres Defteri" bölümüne gider
3. Hiç adres olmadığı için otomatik "Yeni Adres Ekle" popup açılır
4. Bu popup Billing Address olarak kabul edilir
5. **Vergi Müşterisisi Kimlik Numarası** alanı görünür
6. Müşteri gerekli bilgileri doldurur ve kaydeder

### Senaryo 2: Fatura Adresi Ekleme

1. Müşteri "Adres Defteri" > "Yeni Adres Ekle" seçer
2. "Adres Tipi" bölümünden "Fatura Adresi (Billing)" seçer
3. **Vergi Müşterisisi Kimlik Numarası** alanı görünür
4. Formu doldurur ve kaydeder

### Senaryo 3: Teslimat Adresi Ekleme

1. Müşteri "Adres Defteri" > "Yeni Adres Ekle" seçer
2. "Adres Tipi" bölümünden "Teslimat Adresi (Shipping)" seçer
3. **Vergi Müşterisisi Kimlik Numarası** alanı GÖRÜNMEZ
4. Formu doldurur ve kaydeder

### Senaryo 4: Checkout Sayfası

1. Müşteri checkout sayfasına gider
2. Fatura adresi bölümünde **Vergi Müşterisisi Kimlik Numarası** alanı gösterilir
3. Teslimat adresi bölümünde bu alan gösterilmez
4. Ödeme yaparak siparişi tamamlar

## Plugin Açıklamaları

### CheckoutLayoutProcessorPlugin
- **Görev**: Checkout sayfasının JavaScript layout'unu işler
- **İşlev**: 
  - Billing Address formuna `tax_office_number` alanını ekler
  - Shipping Address formundan bu alanı çıkarır
  - Alan konfigürasyonunu sağlar

### AddressRepositoryPlugin
- **Görev**: Adres API'si aracılığıyla adresleri kaydetmeden önce işler
- **İşlev**:
  - Sadece shipping adresleri ise `tax_office_number` verisini temizler
  - Yalnızca billing adreslerde verinin kayıtlanmasını sağlar

### AddressModelPlugin
- **Görev**: Adres model save işleminden önce müdahale eder
- **İşlev**:
  - Shipping-only adreslerin `tax_office_number` alanını kaldırır
  - Veri integrasyonini sağlar

## Öznitelik Bilgileri

**Kod**: `tax_office_number`  
**Tip**: Text  
**Maksimum Uzunluk**: 255 karakter  
**Minimum Uzunluk**: 1 karakter  
**Gerekli**: Hayır (Opsiyonel)  
**Görünürlük**: Evet  
**Sistem Alanı**: Hayır  

**Kullanıldığı Formlar**:
- Müşteri hesabı oluşturma
- Müşteri hesabı düzenleme
- Adres düzenleme
- Müşteri kaydı (checkout)
- Admin adres yönetimi
- Checkout billing address

## Sorun Giderme

### Alan Görünmüyor

1. Modülün etkinleştirildiğini doğrulayın:
   ```bash
   php bin/magento module:status | grep Verimod_TaxOffice
   ```

2. Setup'ı yeniden çalıştırın:
   ```bash
   php bin/magento setup:upgrade
   ```

3. Cache'i temizleyin:
   ```bash
   php bin/magento cache:clean
   ```

4. Static dosyaları dağıtın:
   ```bash
   php bin/magento setup:static-content:deploy -f
   ```

### Shipping Adreslerde Alan Görünüyor

1. Veritabanında şemayı kontrol edin:
   ```bash
   php bin/magento setup:upgrade
   ```

2. Plugin'lerin yüklenip yüklenmediğini doğrulayın:
   ```bash
   php bin/magento module:status
   ```

3. Di compile'ı yeniden çalıştırın:
   ```bash
   php bin/magento setup:di:compile
   ```

## Destek

Sorunlar veya öneriler için GitHub Issues bölümünü kullanın.

## Lisans

Open Software License (OSL 3.0)

## Yazar

Verimod - https://www.verimod.com
