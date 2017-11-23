
def main():
    print("Hello world from py3")
    while True:
        try:
            str = input().split()
            a = int(str[0])
            b = int(str[1])
            print(a + b)
        except Exception:
            break


if __name__ == "__main__":
    main()