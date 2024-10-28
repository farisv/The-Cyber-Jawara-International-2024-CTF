#!/bin/sh

rm web-examplebox.zip
cp -r ../src web-examplebox
echo "CJ{REDACTED}" > web-examplebox/flag.txt
zip -r web-examplebox.zip web-examplebox
rm -r web-examplebox