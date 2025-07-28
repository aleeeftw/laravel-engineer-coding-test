# SAMPL test

- Requirements:
    - PHP 8.4+ (as set in composer.json).
- How to install:
    ```shell
    composer install
    php artisan migrate
    php artisan db:seed --class=TestSeeder
    php artisan serve
    ```
- How to run tests:
    ```shell
    php artisan test
    ```
- How to call endpoint:
    ```shell
    # http://127.0.0.1:8000/api/projects/1/tasks?filterBy[status]=completed&sortBy[assigned_user_name]=desc&offset=0&limit=10
    curl -X GET "http://127.0.0.1:8000/api/projects/1/tasks?filterBy%5Bstatus%5D=completed&sortBy%5Bassigned_user_name%5D=desc&offset=0&limit=10" \
      -H "X-SAMPL-SECRET: random"
    ```
