<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\BackupMail;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Carbon\Carbon;

class BackupController extends Controller
{
    public function index()
    {
        return view('admin_panel.backup.index');
    }

    public function downloadSql()
    {
        try {
            $filename = "backup-" . \date('Y-m-d_H-i-s') . ".sql";
            return \response()->streamDownload(function () {
                echo $this->generateSqlDump();
            }, $filename);
        } catch (\Exception $e) {
            return \back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    private function generateSqlDump()
    {
        $tables = \DB::select('SHOW TABLES');
        $dbName = \config('database.connections.mysql.database');
        $key = "Tables_in_" . $dbName;
        
        $sql = "-- Jan Muhammad & Co Database Backup\n";
        $sql .= "-- Generated: " . \date('Y-m-d H:i:s') . "\n";
        $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

        foreach ($tables as $table) {
            $tableName = $table->$key;
            
            // Get Table Structure
            $createTable = \DB::select("SHOW CREATE TABLE `$tableName`")[0];
            $sql .= "DROP TABLE IF EXISTS `$tableName`;\n";
            $sql .= $createTable->{'Create Table'} . ";\n\n";
            
            // Get Table Data
            $rows = \DB::table($tableName)->get();
            foreach ($rows as $row) {
                $rowArray = (array)$row;
                $columns = \array_keys($rowArray);
                $values = \array_map(function($val) {
                    if (\is_null($val)) return "NULL";
                    return "'" . \addslashes($val) . "'";
                }, \array_values($rowArray));
                
                $sql .= "INSERT INTO `$tableName` (`" . \implode("`, `", $columns) . "`) VALUES (" . \implode(", ", $values) . ");\n";
            }
            $sql .= "\n\n";
        }
        
        $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";
        return $sql;
    }

    public static function sendEmailBackup()
    {
        try {
            $targetEmail = 'janmuhammad1917@gmail.com';
            $filename = "daily-backup-" . \date('Y-m-d') . ".sql";
            
            // Create a temporary instance to call non-static method or just use logic
            $controller = new self();
            $sqlContent = $controller->generateSqlDump();
            
            $storagePath = \storage_path('app/public/backups/');
            if (!\file_exists($storagePath)) { \mkdir($storagePath, 0777, true); }
            
            $filePath = $storagePath . $filename;
            \file_put_contents($filePath, $sqlContent);

            if (\file_exists($filePath)) {
                \Illuminate\Support\Facades\Mail::to($targetEmail)->send(new \App\Mail\BackupMail($filePath, $filename));
                \unlink($filePath);
                return true;
            }
        } catch (\Exception $e) {
            \Log::error('Backup Email Error: ' . $e->getMessage());
        }
        return false;
    }

    public function triggerEmailBackup()
    {
        if ($this->sendEmailBackup()) {
            return \back()->with('success', 'Backup sent to janmuhammad1917@gmail.com successfully!');
        }
        return \back()->with('error', 'Backup failed to send email. Check logs.');
    }
}
