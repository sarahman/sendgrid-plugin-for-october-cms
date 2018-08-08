### Usage Instruction

- Clone this plugin repository and name it to `plugins/pathao/mailer`.

- Go to that folder and run this command:

```bash
composer install
```

- Add the following code in the `config/services.php` file.
 
 ```php
    'sendgrid' => [
        'api_key' => env('SENDGRID_API_KEY'),
    ]
 ```

- Add the value of `SENDGRID_API_KEY` key in the `.env` file.
