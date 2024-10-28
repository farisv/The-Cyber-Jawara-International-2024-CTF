#!/bin/sh

rm web-pdfbox.zip
cp -r ../src web-pdfbox
echo "CJ{REDACTED}" > web-pdfbox/flag.txt
zip -r web-pdfbox.zip web-pdfbox
rm -r web-pdfbox