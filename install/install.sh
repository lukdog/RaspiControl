#!/usr/bin/env bash

if [ $# -ge 2 ]
then
    echo "USAGE: $0 [-d]"
    echo ""
    echo "-d: Install RaspiControl as a DAEMON"
    exit 1
elif [ $# == 1 ]
then
    if [ $1 != '-d' ]
    then
        echo "USAGE: $0 [-d]"
        echo ""
        echo "-d: Install RaspiControl as a DAEMON"
        exit 1
    fi
fi

# Check Needed packages
if ! dpkg -l | grep "apache2" > /dev/null
then
    echo "Apache2 is required, please install it before install RaspiControl"
    exit 1
fi

if ! dpkg -l | grep "mysql-server" > /dev/null
then
    echo "mysql-server is required, please install it before install RaspiControl"
    exit 1
fi

if ! dpkg -l | grep "inotify-tools" > /dev/null
then
    echo "inotify-tools is required, please install it before install RaspiControl"
    exit 1
fi

# Configuration
CONF_DIR="/etc/RaspiControl"
CONF_FILE="config.json"
SCRIPT_NAME="RaspiControl.py"
CMD_NAME="raspicontrol"
CMD_DIR="/usr/local/bin"
RUN_USER=$(whoami)
APACHE_USER=$(sudo ps aux | grep -v root | grep -v $RUN_USER | grep apache2 | cut -d\  -f1 | uniq)

# Install Package
mkdir -p $CONF_DIR >/dev/null 2>&1 || { echo >&2 "Impossible to create config dir $CONF_DIR"; exit 1; }
cp -n $CONF_FILE $CONF_DIR >/dev/null
chmod 640 $CONF_DIR/$CONF_FILE >/dev/null 2>&1 || { echo >&2 "Impossible to change permissions on config file"; exit 1; }
chgrp $APACHE_USER $CONF_DIR/$CONF_FILE >/dev/null 2>&1 || { echo >&2 "Impossible to change permissions on config file"; exit 1; }
cp -n ../tools/$SCRIPT_NAME $CMD_DIR/$CMD_NAME
chmod 500 $CMD_DIR/$CMD_NAME >/dev/null 2>&1 || { echo >&2 "Impossible to change permissions on script file"; exit 1; }


# Install Daemon if requested
if [ $1 -e '-d' ]
then
    DAEMON_NAME="/etc/init.d/raspicontrol"
    SCRIPT_NAME="raspicontrol"
    TOOL_DIR=$(pwd)
    ln -s $TOOL_DIR/$SCRIPT_NAME $DAEMON_NAME >/dev/null 2>&1 || { echo >&2 "Impossible to install daemon"; exit 1; }
fi

exit 0