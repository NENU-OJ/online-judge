//
// Created by torapture on 17-11-14.
//


#include <bits/stdc++.h>
#include <unistd.h>

using namespace std;

int vec[10000000];
void fun1() {
	int a, b;
	int sz = sizeof(vec) / sizeof(int);
	for(int i = 0; i < sz; i += 2) vec[i] = 0;
	while (true) {
		int x, y;
		for (int i = 0; i < 100000000; ++i) x = rand() % sz;
		for (int i = 0; i < 100000000; ++i) y = rand() % sz;

		x = rand() % sz;
		y = rand() % sz;
		if (x == y) y = (y + 1) % sz;
		if (cin >> vec[x] >> vec[y])
			cout << vec[x] + vec[y] << endl;
		else break;
	}
}

void fun2() {
	int a, b;
	while(cin >> a >> b) {
		cout << a + b << endl;
	}
}

int main(int argc, const char *argv[]) {
	srand(time(NULL));
	ios::sync_with_stdio(false);
	//fun2();
	fun1();
	//kill(getpid(), SIGKILL);

//	kill(getpid(), SIGKILL);
//	fun1();
	//fun2();
//	int ppid = getppid();
//	cout << ppid << endl;
//	kill(ppid, SIGKILL);
	return 0;
}