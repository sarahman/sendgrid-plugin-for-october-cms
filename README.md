SendGrid integration plugin

This plugin implements the SendGrid subscription form functionality for the [OctoberCMS](http://octobercms.com).

## Usage Instruction

- Clone this plugin repository and name it to `plugins/sarahman/mailer`.

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

## Configuration

In order to use the plugin you need to get the API key from your [SendGrid account](https://app.sendgrid.com/settings/api_keys).

1. In the OctoberCMS back-end go to the System / Settings page and click the `Mail Configuration` link. 
2. In the `Mail Settings` form,
    - Select the `SendGrid` option in the `Mail method` dropdown.
    - Enter the `SendGrid` API key in the `SendGrid API Key` text box.
    - Then submit this form.

That's it! Happy mailing!!!
