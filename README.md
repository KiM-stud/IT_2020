# IT_2020
Student website project

Instrukcja przygotowania projektu:
1. Należy pobrać oraz zainstalować program XAMPP.
 
2.W folderze w którym zainstalowaliśmy program XAMPP wchodzimy w folder 'htdocs' i tworzymy folder o dowolnej nazwie, np. 'it'.

3.Do właśnie utworzonego folderu kopiujemy pliki projektu.

4.Uruchamiamy program XAMPP przez plik 'xampp-control.exe' znajdujący się w folderze instalacyjnym programu XAMPP.

5.Klikamy przycisk 'Start' obok modułów 'Apache' i 'MySql' tym samym je aktywując.

6.Klikamy przycisk 'Admin' obok modułu 'MySql' - przeniesie nas to na stronę administratora bazy danych.

7.Po lewej stronie klikamy 'Nowa' aby stworzyć nową bazę danych. Nazywamy ją 'it' (Ważne! Jako że plik konfigurujący komunikację z bazą danych jest ustawiony dla bazy danych o nazwie 'it').W innym wypadku należy zmodyfikować pole '$db_name' w pliku 'connect.php' na nazwę stworzonej właśnie bazy danych. Baza danych musi mieć konfigurację 'utf8_polish_ci'.

8.Po wejściu w widok nowostworzonej bazy danych kliknąć 'Import'.

9.Wybrać plik o nazwie 'gracze.sql' z plików projektowych. Powinno to zaimportować przykładową tabelę z użytkownikami do bazy danych.

10.Otworzyć nowe okno w przeglądarce internetowej. Wejść na adres folderu stworzonego w punkcie 5 ,np. 'localhost/*nazwa folderu*/' lub 'localhost/it/' dla nazwy przykładowej.

11. Powyższe akcje powinny dać dostęp do funkcjonalności strony.
