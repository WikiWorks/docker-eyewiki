# EyeWiki Canasta Stack

* Put your Google certificate file at `secrets/google-key.json` (see more at https://www.mediawiki.org/wiki/Extension:GoogleAnalyticsMetrics)
* Create (you can use secrets/_create_empty_secret_files.sh) and populate all the secrets/* files (for testing you can use empty files)
* Create `.env` file based on one of .env.* and alter as needed (or create a symbolic link to one)
* Run `docker compose up -d`
