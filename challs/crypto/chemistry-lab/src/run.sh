#!/bin/bash

socat TCP-LISTEN:4444,reuseaddr,fork EXEC:"/usr/bin/python3 chall.py"