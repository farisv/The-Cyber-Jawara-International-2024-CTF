#include <stdio.h>
#include <string.h>
#include <stdint.h>
#include <stdlib.h>
#include "aes.h"

extern int Nr;
char key[16];

static void phex(uint8_t* str, int bytecount)
{
    for (int i = 0; i < bytecount; ++i)
        printf("%.2x", str[i]);
    printf("\n");
}

char *encrypt(char *plain_text)
{
    struct AES_ctx ctx;
    AES_init_ctx(&ctx, key);
    AES_ECB_encrypt(&ctx, plain_text);
    return plain_text;
}

void menu(){
    printf("1. Set Rounds\n");
    printf("2. Set Plaintext\n");
    printf("3. Encrypt\n");
    printf("4. Reset\n");
    printf("5. Exit\n");
}

int main(void)
{
    setvbuf(stdin, 0, 2, 0);
    setvbuf(stdout, 0, 2, 0);
    setvbuf(stderr, 0, 2, 0);
    FILE *key_file = fopen("flag.txt", "r");
    if (key_file == NULL) {
        perror("Failed to open flag.txt");
        exit(0);
    }
    fread(key, 1, 16, key_file);
    fclose(key_file);
    int bytecount = 16;
    Nr = 10;
    char txt[16];
    int *ptr;
    char *ptr2;
    while(1){
        menu();
        int choice;
        printf("Enter choice: ");
        scanf("%d", &choice);
        getchar();
        switch(choice){
            case 1:
                ptr = (int *)malloc(16);
                printf("4 Rounds (8 bytes output)\n");
                printf("7 Rounds (12 bytes output)\n");
                printf("10 Rounds (full 16 bytes output)\n");
                printf("Enter number of rounds: ");
                scanf("%d", ptr);
                getchar();
                if (*ptr == 4){
                    bytecount = 8;
                } else if (*ptr == 7){
                    bytecount = 12;
                } else if (*ptr == 10){
                    bytecount = 16;
                } else {
                    printf("Invalid number of rounds\n");
                }
                break;
            case 2:
                ptr2 = (char *)malloc(16);
                printf("Enter plaintext: ");
                for (int i = 0; i < 16; i++){
                    ptr2[i] = getchar();
                }
                getchar();
                break;
            case 3:
                memcpy(txt, ptr2, 16);
                Nr = *ptr;
                encrypt(txt);
                printf("Encrypted: ");
                phex(txt, bytecount);
                break;
            case 4:
                free(ptr);
                free(ptr2); 
                Nr = 10;
                bytecount = 16;
                break;
            case 5:
                exit(0);
                break;
            default:
                printf("Invalid choice\n");
        }
    }
    return 0;
}