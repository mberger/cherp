#!/bin/sh
while true;
do
PROCESS=`ps aux | grep tweet | grep -v grep`
if [ "$PROCESS" = "" ]
then
# Not found - start process
./startstuff
/usr/bin/mail -s "restarted" mberger@internetinnovation.com
else
# Found it - don't do anything

echo "found it"
fi
sleep 300
done
