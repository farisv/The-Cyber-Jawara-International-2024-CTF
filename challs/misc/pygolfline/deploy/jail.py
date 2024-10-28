#!/usr/local/bin/python3 -S

SAFE = {'__builtins__': {}}
BANNED = '*`$"|\'}/=><%~,;&?[!]{^\\-(+)#'
BANNED += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'

def has_unsafe_character(string):
    for s in set(string):
        if s in BANNED:
            return True

def run_code(string):
    for k in (b:=__builtins__.__dict__):
        if k == 'exec':
            exc = b[k]
        b[k] = None

    exc(string, SAFE, SAFE)

code = "__builtins__['__build_class__'] = lambda*x:None\n"
while (inp:=input()) != "EOF":
    if len(inp) > 21:
        assert 0, 'Too long'
    if not inp.isascii():
        assert 0, 'No unicode allowed'
    if has_unsafe_character(inp):
        assert 0, 'Unsafe character detected'

    code += inp + "\n"

assert len(code.splitlines()) < 55, 'Too much lines'

try:
    run_code(code)
except:
   pass