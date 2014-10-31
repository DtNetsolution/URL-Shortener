URL Shortener
=============

Ein URL Shorter mit Unterstützung für mehrere Instanzen.

Einrichtung
-----------

1. Das Repo in einen Ordner auf dem Webserver clonen.
2. URL/admin/setup.php aufrufen
3. Zugangsdaten für die Datenbank eingeben. Der Benutzer muss Tabellen erzeugen können.
4. Den Domain Host und Pfad eingeben. Beispiel:
	- `http://go.dtnet.de/`: Host `go.dtnet.de`, Pfad `/`
	- `http://www.softwaredemo.de/url-shortener/`: Host `www.softwaredemo.de`, Pfad `/url-shortener/`
5. Das Formular absenden

Um mehrere Instanzen zu betreiben, müssen alle URLs auf den selben Ordner mit dem PHP-Code zugreifen. Danach werden automatisch die Instanzen unterschieden. Neue Instanzen müssen manuell in der Tabelle `application` angelegt werden. 

**Hinweis:** Es kann nur eine Instanz pro Domain Host betrieben werden.

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