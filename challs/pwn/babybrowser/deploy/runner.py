import sys
import uuid
import subprocess

filename = f"/tmp/{str(uuid.uuid4())}"

print("Please send your own payload! (receive until '<EOF>'), Max: 100000bytes", flush=True)

byt = 0
with open(filename, "w") as f:
    while byt < 100000:
        line = sys.stdin.readline()

        if "<EOF>" in line:
            break

        if len(line) + byt > 100000:
            print(f"{byt + len(line)} > 100000, Assert", flush=True)
            exit(-1)
        else:
            f.write(line)

subprocess.run(["/home/pwn/d8", f"{filename}"])