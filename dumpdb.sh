#!/bin/bash

sql_exportfile='packpal_dump.sql'
dev_db='packpal'
dev_db_user='packpal_dev'
dev_db_pwd='plop0999'
tables=''


#===============================================================================

function ask {
    # http://djm.me/ask
    while true; do
        if [ "${2:-}" = "Y" ]; then
            prompt="Y/n"
            default=Y
        elif [ "${2:-}" = "N" ]; then
            prompt="y/N"
            default=N
        else
            prompt="y/n"
            default=
        fi

        # Ask the question
        read -p "$1 [$prompt] " REPLY

        # Default?
        if [ -z "$REPLY" ]; then
            REPLY=$default
        fi

        # Check if the reply is valid
        case "$REPLY" in
            Y*|y*) return 0 ;;
            N*|n*) return 1 ;;
        esac

    done
}

# Make sure this isn't run accidentally
ask "Database $dev_db $tables dumpen naar $sql_exportfile??" Y || exit

mysqldump --opt -u $dev_db_user -p$dev_db_pwd $dev_db $tables > /vagrant/sql/$sql_exportfile || exit
echo "Database geexporteerd."
