#include <stdio.h>

int main() {
	setvbuf(stdout, NULL, _IONBF, 0);
	setvbuf(stdin, NULL, _IONBF, 0);
	setvbuf(stderr, NULL, _IONBF, 0);
    char buf[32];
    read(0, buf, 0x1000);
    return 0;
}