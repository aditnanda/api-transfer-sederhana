# Cridential (default)
- User : user@boscod.com
- pass : rahasia

- User : aditya.nanda0030@gmail.com
- pass : 12345678


## Installation
1. Clone Projek ini
    ```bash
    git clone https://github.com/aditnanda/api-transfer-sederhana.git
    cd api-transfer-sederhana
    ```
2. Install dependencies
    ```bash
    composer install
    ```
    

3. Set up Konfigurasi Laravel
    ```bash
    copy .env.example .env
    php artisan key:generate
    ```

4. Setting databasemu di .env

5. Migrate database dan lakukan seeding lalu install passport
    ```bash
    php artisan migrate --seed
    ```

     ```bash
    php artisan passport:install 
    ```

6. Serve Aplikasi
    ```bash
    php artisan serve
    ```

## Documentation
APP_URL/api/documentation

## How to update L5 Swagger UI
You must run `php artisan l5-swagger:generate`

