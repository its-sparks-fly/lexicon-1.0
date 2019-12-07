# Lexicon 1.0
Dieses Plugin erweitert euer MyBB-Forum ein Lexikon.

# Im Detail
Im Admin CP könnt ihr Kategorien sowie Einträge anlegen. Einträge werden, falls vorhanden, von euch einer Kategorie zugeordnet. 
Eure User können das Lexikon nach diesen Kategorien und Einträgen durchsuchen, sowie eine Übersicht aller Einträge mit entsprechenden Buchstaben sehen. Einträge können von euch direkt verlinkt werden, in dem ihr über das Keyword oder die Kategorie verlinkt.

Zum Beispiel:
euerforum.de/lexicon.php?action=list&keyword=waffengesetze (ruft den Artikel "Waffengesetze" auf)
oder
euerforum.de/lexicon.php?action=list&category=1 (ruft alle Artikel auf, die zur Kategorie "1" gehören - IDs im ACP einsehbar)

# Plugin installieren
Alle Dateien müssen dem Dateibaum im Ordner entsprechend in euer Forum hochgeladen werden.
Language-Packs: inc/languages/deutsch_du, inc/languages/deutsch_sie (inkl. Admin-Datei)
Hauptverzeichnis: lexicon.php
Plugin-Verzeichnis: inc/lexicon.php

# Datenbankänderungen
Neue Tabellen: lexicon_categories & lexicon_entries
Neue Templates: lexicon, lexicon_list, lexicon_list_bit, lexicon_list_none, lexicon_nav, lexicon_nav_bit

# Design & Style
Das Lexikon nutzt nur Klassen aus MyBB - tcat, thead, trow, tborder.
Die Templates lassen sich jedoch komplett durchstylen.
In den Artikeln ist HTML sowie BBCode erlaubt. 
Texte lassen sich in den entsprechenden Language-Files bearbeiten.

# Wichtige Links
Lexikon: euerforum.de/lexicon.php
Adminbereich: euerforum.de/admin/index.php?module=config-lexicon 
Der Adminbereich wurde in den Reiter "Konfiguration" hinzugefügt =>  "Lexikon verwalten" 


# Screenshots

<img src="https://snipboard.io/hHrZCL.jpg" />

<img src="https://snipboard.io/4nqiLB.jpg" />

<img src="https://snipboard.io/XWlo0Q.jpg" />
