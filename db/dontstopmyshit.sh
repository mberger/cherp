#!/bin/sh
while true;
do
PROCESS=`ps aux | grep tweet | grep -v grep`
if [ "$PROCESS" = "" ]
then
# Not found - start process
nohup ./startstuff > /dev/null &
else
# Found it - don't do anything

echo "found it"
fi
sleep 300
done
