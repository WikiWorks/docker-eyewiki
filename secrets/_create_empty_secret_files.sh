#!/bin/bash

DIR=$( cd -- "$( dirname -- "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )

touch -a "$DIR/db_root_password.txt"
touch -a "$DIR/smtp_password.txt"
touch -a "$DIR/wgSentryDsn.txt"
touch -a "$DIR/google-key.json"
touch -a "$DIR/massmessage_bot_password.txt"
