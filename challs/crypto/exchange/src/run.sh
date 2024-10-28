#!/bin/bash

socat TCP-LISTEN:20003,reuseaddr,fork EXEC:"python3 -B server.py"