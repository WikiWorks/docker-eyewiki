# DO NOT PUT SECRETS INTO THIS FILE (use docker secrets and files in the secrets directory)
COMPOSE_FILE=compose.yml:compose.staging.auth.yml

MW_SITE_SERVER=https://eyewiki-staging.wikiworks.com
MW_SITE_FQDN=eyewiki-staging.wikiworks.com
PHP_UPLOAD_MAX_FILESIZE=10M
PHP_POST_MAX_SIZE=10M
PHP_MAX_INPUT_VARS=1000

# eyewiki-massmessage-apiscripts TEST MODE
MASSMESSAGE_BOT_USER=WikiWorks4@ApiSender
# secrets/massmessage_bot_password.txt

# Enable it on PRODUCTION wiki only
MW_ENABLE_SITEMAP_GENERATOR=false

# Use less resources on staging wiki
ES_MEMORY=128m
MW_JOB_RUNNER_PAUSE=20
VARNISH_SIZE=50m

BASIC_USERNAME=admin
# Generate with `openssl passwd -apr1 MY_PASSWORD_RAW`, the password below is `admin`
BASIC_PASSWORD='$apr1$1pU./GTq$MdRRfwmm.WIyB3CKoAEjm1'
