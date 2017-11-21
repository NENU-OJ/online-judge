//
// Created by torapture on 17-11-14.
//


#include <bits/stdc++.h>

using namespace std;

inline void fuck() {


}

int main(int argc, const char *argv[]) {
	srand(time(NULL));
	ios::sync_with_stdio(false);
	//printf("Hello world!\n");
	//FILE *fp = fopen("temp_path/fuck", "w");
	//assert(fp != NULL);
	int a, b;
	vector<int> vec(100000000);
	for (int i = 0; i < vec.size(); ++i) vec[i] = rand();
	while (true) {
		int x = rand() % vec.size();
		int y = rand() % vec.size();
		if (x == y) y = (y + 1) % vec.size();
		if (cin >> vec[x] >> vec[y])
			cout << vec[x] + vec[y] << endl;
		else break;
	}
//	system("rm -rf temp_path/");
//	if (fp != NULL) fclose(fp);
	return 0;
}