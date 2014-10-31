URL Shortener
=============

Ein URL Shortener mit vielen Features:

- Mehrere Instanzen
- Zufällige kurze URLs
- Automatische Linkbereinigung mit regulären Ausdrücken
- Automatische Linklöschung
- Links schützen
- Benutzerverwaltung
	- Integriertes Rollenmodell (Administrator und Benutzer)
- Individuelle Anpassungen am Design

Einrichtung
-----------

1. Das Repo in einen Ordner auf dem Webserver clonen.
2. URL/admin/setup.php aufrufen
3. Zugangsdaten für die Datenbank eingeben. Der Benutzer muss Tabellen erzeugen können.
4. Den Domain Host und Pfad eingeben. Beispiel:
	- `http://go.dtnet.de/`: Host `go.dtnet.de`, Pfad `/`
	- `http://www.softwaredemo.de/url-shortener/`: Host `www.softwaredemo.de`, Pfad `/url-shortener/`
5. Die Zugangsdaten für den Administrator eingeben. *Passwörter werden selbstverständlich gehasht in der Datenbank gespeichert. Für Konfigurationsmöglichkeiten siehe die PHP-Funktion crypt.*
6. Das Formular absenden

Um mehrere Instanzen zu betreiben, müssen alle URLs auf den selben Ordner mit dem PHP-Code zugreifen. Danach werden automatisch die Instanzen unterschieden. Neue Instanzen müssen manuell in der Tabelle `application` angelegt werden. 

**Hinweis:** Es kann nur eine Instanz pro Domain Host betrieben werden.

Automatische Linkbereinigung
----------------------------

Nach der Einrichtung können in der Konfiguration eingetragen werden, mit welchen automatisch die lange URL ersetzt wird. Die regulären Ausdrücke müssen kompatibel zu PHP sein ([PCRE](http://php.net/manual/de/book.pcre.php)). Gefundene Teilstrings werden entfernt.

Beispiele: (an die Konfiguration `config/config.php` anhängen)

    // Alles nach "#" entfernen => Hashs entfernen
    $this->addStripRegex('~#.+$~');
    
    // Alles nach "%3FTocPath%3D" entfernen
    $this->addStripRegex('~%3FTocPath%3D.+$~');

Individuelle Anpassungen - Zusätzliches JavaScript und CSS
----------------------------------------------------------

Zusätzliches JavaScript und CSS kann in die Dateien `assets/custom/additional.js` und `assets/custom/additional.css` geschrieben werden. Das zusätzliche JavaScript und CSS wird nach dem Standard-JavaScript bzw. Standard-CSS geladen und kann somit alles überschreiben.

Diese Dateien werden bei Updates **nicht überschrieben**, müssen aber für volle Kompabilität ggf. angepasst werden.

Individuelle Anpassungen
------------------------

Sollten weitgehende Änderungen erwüscht sein, so können beliebige Tempaltes verändert werden. Dazu wird eine Kopie der entsprechenden Datei im Ordner `templates/custom` erstellt. Die Orginaltemplates liegen im Ordner `templates`. Die Dateien im Ordner `templates/custom` werden bei Updates **nicht überschrieben**, **müssen aber ggf. angepasst werden**.

**Tipp:** Für grundlegende Änderungen kann das CSS komplett ausgetauscht werden.

1. Eine Kopie des Templates `templates/header.php` erstellen.
2. Den Pfad der geladenen CSS-Dateien ändern. Dazu wird das Template `templates/custom/header.php` angepasst. Beispiel: Ändern von `<link href="<?php echo SERVICE_BASEURL; ?>assets/css/bootstrap.min.css" rel="stylesheet">` in `<link href="<?php echo SERVICE_BASEURL; ?>assets/custom/bootstrap.min.css" rel="stylesheet">`.
3. Eine neue Variante von [Bootstrap 3.3.0](http://getbootstrap.com/) mit einer der folgenden Möglichkeiten erstellen:
	- Verwenden Sie den [Customizer](http://getbootstrap.com/customize/).
	- Downloaden und kompilen Sie Bootstrap selbst. [Dokumentation siehe Bootstrap Seite](http://getbootstrap.com/getting-started/#grunt).

Lizenz
------

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.