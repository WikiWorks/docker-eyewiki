#!/bin/bash

PHP_SCRIPT_NAME=$1

if [ -z "$PHP_SCRIPT_NAME" ]; then
    echo "Error: No php script name passed."
    exit 1
fi

if [ ! -f "$PHP_SCRIPT_NAME" ]; then
    echo "Error: File '$PHP_SCRIPT_NAME' does not exist."
    exit 1
fi

isTrue() {
    case $1 in
        "True" | "TRUE" | "true" | 1)
            return 0
            ;;
        *)
            return 1
            ;;
    esac
}

if [ ! -d "vendor" ]; then
  composer install --ignore-platform-reqs --no-scripts --no-interaction
fi

if [ -z "$MASSMESSAGE_SITE_URL" ]; then
    echo "MASSMESSAGE_SITE_URL not set"
    exit 1
fi

if [ -z "$MASSMESSAGE_BOT_USER" ]; then
    echo "MASSMESSAGE_BOT_USER not set"
    exit 1
fi

# Check if bot password is not empty
if [ -z "$MASSMESSAGE_BOT_PASSWORD" ]; then
    if [ -z "$MASSMESSAGE_BOT_PASSWORD_FILE" ]; then
        echo "MASSMESSAGE_BOT_PASSWORD not set"
        exit 1
    fi
    # Check if bot_password file is not empty and does not contain only empty lines
    if [[ ! -s "$MASSMESSAGE_BOT_PASSWORD_FILE" ]] || ! grep -q '[^[:space:]]' "$MASSMESSAGE_BOT_PASSWORD_FILE"; then
        echo "The password file '$MASSMESSAGE_BOT_PASSWORD_FILE' is empty"
        exit 1
    fi
fi

if isTrue "$MASSMESSAGE_LIVE_MODE"; then
    # LIVE MODE
    if [ -z "$MASSMESSAGE_SPAMLIST_CATEGORY" ]; then
        echo "MASSMESSAGE_SPAMLIST_CATEGORY not set"
        exit 1
    fi
    php "$PHP_SCRIPT_NAME" --live
else
    # TEST MODE
    php "$PHP_SCRIPT_NAME"
fi
