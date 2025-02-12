# 🦊 Lišákův obchod API

Tento projekt je jednoduché **REST API** pro evidenci produktů v obchodě. Umožňuje správu produktů, sledování historie cen, vyhledávání a filtrování.

---

## **📌 Požadavky**

-   **Docker + Docker Compose**
    -   **Laravel v11.42.0**
    -   **PHP v8.3.14**
    -   **Composer**
    -   **SQLite**

---

## **🚀 Instalace a spuštění**

### **🔹 Spuštění přes Docker**

Nejrychlejší způsob, jak spustit projekt.

```bash
git clone https://github.com/icedhydro/lisakuv-obchod.git
cd lisakuv-obchod
docker-compose up --build
```

📌 **Po spuštění bude OpenAPI dokumentace dostupná na:**  
[http://0.0.0.0:8000/api/documentation/](http://0.0.0.0:8000/api/documentation/)

Pokud chceš aplikaci vypnout:

```bash
docker-compose down
```

---

### **🔹 Spuštění bez Dockeru**

Pokud chceš spustit projekt ručně bez Dockeru.

#### **1. Naklonování repozitáře**

```bash
git clone https://github.com/icedhydro/lisakuv-obchod.git
cd lisakuv-obchod
```

#### **2. Instalace závislostí**

```bash
composer install
```

#### **3. Nastavení `.env` souboru a vygenerování klíče**

```bash
cp .env.example .env
php artisan key:generate
```

#### **4. Nastavení databáze SQLite**

```ini
DB_CONNECTION=sqlite
```

#### **5. Vytvoření databázového souboru**

```bash
touch database/database.sqlite
```

#### **6. Spuštění migrací a seedování**

```bash
php artisan migrate:fresh --seed
```

#### **7. Spuštění aplikace**

```bash
php artisan serve
```

---

## **📚 Dokumentace API (Swagger UI)**

Swagger dokumentaci vygeneruješ a zobrazíš takto:

```bash
php artisan l5-swagger:generate
```

📌 Přístup k dokumentaci:  
[http://127.0.0.1:8000/api/documentation](http://127.0.0.1:8000/api/documentation)

---

## **🔥 Testování API pomocí cURL**

### 🔹 Seznam všech produktů

```bash
curl -X GET "http://127.0.0.1:8000/api/products"
```

### 🔹 Vytvoření nového produktu

```bash
curl -X POST "http://127.0.0.1:8000/api/products" \
     -H "Content-Type: application/json" \
     -d '{"name": "Jablko", "price": 20.5, "stock": 50}'
```

### 🔹 Aktualizace produktu

```bash
curl -X PUT "http://127.0.0.1:8000/api/products/1" \
     -H "Content-Type: application/json" \
     -d '{"price": 25.0, "stock": 40}'
```

### 🔹 Smazání produktu

```bash
curl -X DELETE "http://127.0.0.1:8000/api/products/1"
```

### 🔹 Vyhledání produktu podle názvu

```bash
curl -X GET "http://127.0.0.1:8000/api/products/search?name=Hruška"
```

### 🔹 Filtrování podle počtu kusů na skladu

```bash
curl -X GET "http://127.0.0.1:8000/api/products/filter?stock_min=10&stock_max=100"
```

---

## **✅ PHPUnit Testy**

Pro spuštění testů:

```bash
php artisan test --filter ProductApiTest
```

---

## **📌 Technologie**

-   **Laravel v11.42.0**
-   **PHP v8.3.14**
-   **SQLite**
-   **Swagger - OpenAPI**
-   **PHPUnit**
-   **Docker + Docker Compose**

---

## **📌 Poznámka**

Tento projekt byl vytvořen podle technického zadání **Foxentry** s cílem ukázat mé schopnosti v PHP vývoji.
