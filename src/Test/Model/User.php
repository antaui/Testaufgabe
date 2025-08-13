<?php
namespace Test\Model;

class User {
    private $file;

    public function __construct($file) {
        $this->file = $file; // Hier wissen wir: das Objekt arbeitet mit dieser CSV
    }

    // Benutzer anhand des Usernamens suchen
    public function getUserByUsername($username) {
        $rows = $this->readCSV();
        foreach ($rows as $row) {
            if ($row['username'] === $username) {
                return $row; // Array mit Benutzerinfos
            }
        }
        return false; // Nicht gefunden
    }

    // Fehlversuche erhöhen
    public function incrementFailedAttempts($username) {
        $rows = $this->readCSV();
        foreach ($rows as &$row) {
            if ($row['username'] === $username) {
                $row['failed'] = intval($row['failed']) + 1;
            }
        }
        $this->writeCSV($rows);
    }

    // Fehlversuche zurücksetzen nach erfolgreichem Login
    public function resetFailedAttempts($username) {
        $rows = $this->readCSV();
        foreach ($rows as &$row) {
            if ($row['username'] === $username) {
                $row['failed'] = 0;
            }
        }
        $this->writeCSV($rows);
    }

    // Benutzer sperren
    public function blockUser($username) {
        $rows = $this->readCSV();
        foreach ($rows as &$row) {
            if ($row['username'] === $username) {
                $row['blocked'] = 1;
            }
        }
        $this->writeCSV($rows);
    }

    // Letztes Login aktualisieren
    public function updateLastLogin($username) {
        $rows = $this->readCSV();
        foreach ($rows as &$row) {
            if ($row['username'] === $username) {
                $row['lastlogin'] = date("Y-m-d H:i:s");
            }
        }
        $this->writeCSV($rows);
    }

    // Hilfsmethode: CSV auslesen
    private function readCSV() {
        $rows = [];
        if (($handle = fopen($this->file, 'r')) !== false) {
            $header = fgetcsv($handle); // erste Zeile = Spaltennamen
            while (($data = fgetcsv($handle)) !== false) {
                $rows[] = array_combine($header, $data); // Array mit Spaltennamen als Keys
            }
            fclose($handle);
        }
        return $rows;
    }

    // Hilfsmethode: CSV schreiben
    private function writeCSV($rows) {
        if (($handle = fopen($this->file, 'w')) !== false) {
            fputcsv($handle, array_keys($rows[0])); // Header schreiben
            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        }
    }
}
