# EyeWiki MassMessage sender script

## Details

### MassMessageAPI.php

The script is used to find "spam list" pages from pre-defined category and
send out emails to collected recipients with pre-defined subject. Content of
emails is sourced from page path, eg:

`Category:TestSpamList` contains the page:

* EyeWiki:Test/SpamList/MessageRecipients

And `settings.php` is configured as below:

```php
...
$settings['spamlistCategory'] = 'Category:TestSpamList';
...
```

- The script will find all pages under the `TestSpamList` category
- For each page (eg.: `EyeWiki:Test/SpamList/MessageRecipients`) it will do title transformation and use `EyeWiki:Test/SpamList/Message` as email contents
- Then it will call MassMessage `massmessage` API endpoint feeding it with the page (eg.: `EyeWiki:Test/SpamList/MessageRecipients` in this case)
- MassMessage will send out emails to all the recipients listed on the page (via `{{#target:User:XXX}}`)

Note: the subject of emails will be composed as below:

```
Root page name + : + Month name + EyeWiki Update
```

In the example above it will be `Test/SpamList: August EyeWiki Update`

See `settings.sample.php` for configuration example.

### stalepages.php

The script fetches recipients from pre-defined `Project:Stale pages/Notification list` page (recipients defined via `{{#target:User:XXX}}`)
and send out emails using `Project:Stale pages/Message` as mail contents.

## Setup

* Clone the repo
* Run `composer install`
* Copy `settings.sample.php` to `settings.php` and modify as needed

## Usage

TEST mode:

```bash
php MassMessageAPI.php
```

In test mode the script will ignore `spamlistCategory` setting from settings file and will instead
overwrite it with `Category:TestSpamList`. Ensure you have these pages on wiki before testing.

PRODUCTION mode:

```bash
php MassMessageAPI.php --live
```

The script will wait for 10 seconds before starting to send out emails, so you'll have a chance to abort.
