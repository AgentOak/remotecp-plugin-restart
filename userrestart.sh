#!/bin/sh

PATH="/usr/local/sbin:/usr/sbin:/sbin:/usr/local/bin:/usr/bin:/bin"

#if [ "$1" -ne "tmfserver" ] && [ "$1" -ne "rcplive" ]; then
#	echo "Unknown service" >&2
#	exit 4
#fi

if [ "$2" -ne "start" ] && [ "$2" -ne "restart" ] && [ "$2" -ne "stop" ] && [ "$2" -ne "status" ]; then
	echo "Invalid operation" >&2
	exit 2
fi


if [ $(id -u) -ne 0 ]; then
	sudo -n "$0" "$1" "$2"
	exit $?
fi

service "$1" "$2"
