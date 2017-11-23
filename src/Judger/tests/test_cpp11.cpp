//
// Created by torapture on 17-11-14.
//

#include <bits/stdc++.h>

using namespace std;

int main(int argc, const char *argv[]) {
	vector<int> vec = {1, 2, 3, 4, 5};
	int sum = accumulate(vec.begin(), vec.end(), 0, [](int x, int y) -> int { return x + y; });
	cout << "sum = " << sum << endl;
	return 0;
}