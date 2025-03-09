# EyeWiki Canasta Stack

* Put your Google certificate file at `secrets/google-key.json` (see more at https://www.mediawiki.org/wiki/Extension:GoogleAnalyticsMetrics)
* Create (you can use secrets/_create_empty_secret_files.sh) and populate all the secrets/* files (for testing you can use empty files)
* Create `.env` file based on one of .env.* and alter as needed (or create a symbolic link to one)
* Run `docker compose up -d`

## A note about data locations

### Bind mounts
Used to just binding a certain directory or file from the host inside the container. We use:
- `./_initdb` directory is used to pass the database dump for stack initialization

### Named volumes
Data that must be persistent across container life cycles are stored in docker volumes:
- `db_data` (MySQL databases and working directories, attached to `db` service)
- `elasticsearch_data` (Elasticsearch nodes, attached to `elasticsearch` service)
- `web_data` (Miscellaneous MediaWiki files and directories that must be persistent by design, attached to the corresponding `web` service )
- `images` (MediaWiki upload directory, attached to `web` service and used in `restic` service (read-only))
- `restic_data` (Backup data exchange, attached to `restic` service)
- `varnish_data` (Cache data of `varnish` proxy service)