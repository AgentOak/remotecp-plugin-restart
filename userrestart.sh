#!/bin/sh
# This file is part of remotecp-plugin-restart

PATH="/usr/local/sbin:/usr/sbin:/sbin:/usr/local/bin:/usr/bin:/bin"

if [ "$1" != "tmfserver" ] && [ "$1" != "rcplive" ]; then
	echo "Unknown service" >&2
	exit 150
fi

if [ "$2" != "start" ] && [ "$2" != "restart" ] && [ "$2" != "stop" ] && [ "$2" != "status" ]; then
	echo "Invalid operation" >&2
	exit 151
fi

service "$1" "$2"
