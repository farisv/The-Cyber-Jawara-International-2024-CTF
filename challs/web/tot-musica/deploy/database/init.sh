#!/bin/sh

PASSWORD=$(head /dev/urandom | md5sum | tr -d '\n -')

# submit the flag using this CJ{full_name}; ex: CJ{this is the placeholder flag abcdef}; 
# full_name regex = "[a-z0-9\s]"
# 

# MD5 Real Flag
MD5_FLAG="1e01439b967b8eef44320234e2cd57ca"

echo "use red;
INSERT INTO users (username, full_name, passwd, roles) VALUES ('uta', '$FLAG', '$MD5_FLAG', 1);
INSERT INTO users (username, full_name, passwd, roles) VALUES ('ado1024imokenp', 'Ado', '$PASSWORD', 0);
" > /tmp/tmp.sql

mysql -u $MYSQL_USER -p$MYSQL_PASSWORD < /tmp/tmp.sql
