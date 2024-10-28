/*
 * Cyber Jawara International 2024 - Stone Game (Misc)
 *
 * gcc stone.c -o stone
 * socat TCP4-LISTEN:10001,reuseaddr,fork EXEC:"timeout 30 ./stone" > /dev/null 2>&1 &
*/

#include <stdio.h>
#include <stdlib.h>
#include <time.h>

int stones[7] = {3, 4, 5, 2, 6, 3, 5}; 
int n = 7;
int chance = 0;

int getRandomNumber(int n) {
  return (rand() % n) + 1;
}

void displayStones() {
  printf("\nStones:\n");
  printf("+---+---+---+---+---+---+---+\n");

  for (int i = 0; i < n; i++) {
    printf("| %d ", stones[i]);
  }
  printf("|\n");

  printf("+---+---+---+---+---+---+---+\n");

  for (int i = 0; i < n; i++) {
    printf("  %d ", i + 1);
  }
  printf("\n\n");
}

int computeNimSum() {
  int nimSum = 0;
  for (int i = 0; i < n; i++) {
    nimSum ^= stones[i];
  }
  return nimSum;
}

void playerTurn() {
  int choice, amount;
  while (1) {
    printf("Your turn! Choose a set of stones (1-%d): ", n);
    scanf("%d", &choice);
    choice--;  // Convert to zero-indexed

    if (choice < 0 || choice >= n || stones[choice] == 0) {
      printf("Invalid choice! Try again.\n");
      continue;
    }

    printf("How many stones to take from set %d? ", choice + 1);
    scanf("%d", &amount);

    if (amount < 0 || amount > stones[choice]) {
      printf("Invalid amount! Try again.\n");
      continue;
    }

    if (amount == 0) {
      if (chance == 1) {
        printf("Invalid amount! Try again.\n");
        continue;
      }
      chance = 1;
    }

    stones[choice] -= amount;
    break;
  }
}

void computerTurn() {
  int nimSum = computeNimSum();
  if (nimSum == 0) {
    // If the Nim Sum is already 0, the computer plays randomly
    for (int i = 0; i < n; i++) {
      if (stones[i] > 0) {
        int amount = getRandomNumber(stones[i]);
        stones[i] -= amount;
        printf("Computer takes %d stone(s) from set %d\n", amount, i + 1);
        return;
      }
    }
  }

  // Find the optimal move
  for (int i = 0; i < n; i++) {
    int target = stones[i] ^ nimSum;
    if (target < stones[i]) {
      printf("Computer takes %d stone(s) from set %d\n", stones[i] - target, i + 1);
      stones[i] = target;
      return;
    }
  }
}

int isGameOver() {
  for (int i = 0; i < n; i++) {
    if (stones[i] > 0) {
      return 0;
    }
  }
  return 1;
}

void init() {
  char buff[1];
  buff[0] = 0;
  setvbuf(stdout, buff, _IOFBF, 1);
  srand(time(0));
}

int main() {
  init();

  printf("Welcome to the Stone Game! Try to beat the computer.\n\n");
  printf("Rules:\n");
  printf("1. There are 7 sets of stones. Each set has a certain number of stones.\n");
  printf("2. On your turn, pick any set and remove 1 or more stones from it.\n");
  printf("3. You must take at least one stone, and you cannot take more than what is available.\n");
  printf("4. The player who takes the last stone wins!\n\n");

  while (1) {
    displayStones();

    if (isGameOver()) {
      printf("You lost! The computer wins.\n\n");
      break;
    }

    playerTurn();
    displayStones();

    if (isGameOver()) {
      printf("Congratulations! You win.\n");
      printf("CJ{why_I_allowed_zero_:(}\n\n");
      break;
    }

    computerTurn();
  }

  return 0;
}
