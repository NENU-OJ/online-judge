import java.util.*;

public class Main {
    static public void main(String[] args) {
        //System.out.println("Hello world from Java");
        Scanner scan = new Scanner(System.in);
        while(scan.hasNextInt()) {
            System.out.println(scan.nextInt() + scan.nextInt());
        }
        scan.close();
    }
}