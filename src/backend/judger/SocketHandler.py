import socket

class SocketHandler(object):
    def __init__(self, port):
        self.server = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        self.server.bind(('127.0.0.1', port))
        self.server.listen()

    def get_rid(self):
        sock, addr = self.server.accept()
        rid = sock.recv(1024).decode('utf-8')
        sock.close()
        return rid


def main():
    socket_handler = SocketHandler(27016)
    for i in range(5):
        rid = socket_handler.get_rid()
        print(rid)

if __name__ == '__main__':
    main()
