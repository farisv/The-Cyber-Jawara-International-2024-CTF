#!/usr/bin/env python3
from RestrictedPython import compile_restricted
from RestrictedPython import utility_builtins
from RestrictedPython import safe_globals

import sys

code = input('Enter code: ')
banned = '$[#<@;-}>?!&"\']|/^{+%~`\\'

for char in set(code):
    if char in banned:
        exit('Banned character detected')

try:
    byte_code = compile_restricted(code, '<string>', 'exec')
    exec_globals = {**safe_globals, **utility_builtins}

    sys.modules['os'].__dict__.clear()
    sys.modules['posix'].__dict__.clear()
    sys.modules.clear()
    for _ in sys.__dict__:
        sys.__dict__[_] = None
    del sys

    exec(byte_code, exec_globals, {})
except:
    pass