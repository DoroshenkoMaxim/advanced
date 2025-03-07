<?php

use yii\db\Migration;

class m250305_154153_generate_users extends Migration
{
    public function safeUp()
    {
        $count = 10;      // Общее количество пользователей
        $chunkSize = 2;    // Размер каждого чанка (по 10 пользователей)
        $numChunks = ceil($count / $chunkSize);
        $maxProcesses = 2;    // Максимальное количество одновременно запущенных процессов

        echo "Начинаем генерацию пользователей в $numChunks чанках по $chunkSize пользователей...\n";

        // Абсолютные пути:
        $php = 'C:\OSPanel\modules\php\PHP_7.2\php.exe';
        $projectDir = 'C:\OSPanel\domains\advanced';
        $yii = $projectDir . '\yii';

        $processes = []; // Массив для хранения запущенных процессов

        for ($chunk = 0; $chunk < $numChunks; $chunk++) {
            // Если достигли лимита, ждем, пока какой-либо процесс завершится
            while (count($processes) >= $maxProcesses) {
                foreach ($processes as $key => $procData) {
                    $status = proc_get_status($procData['process']);
                    if (!$status['running']) {
                        proc_close($procData['process']);
                        unset($processes[$key]);
                    }
                }
                if (count($processes) >= $maxProcesses) {
                    sleep(1);
                }
            }

            $start = $chunk * $chunkSize + 1;
            $end = min(($chunk + 1) * $chunkSize, $count);

            // Формируем уникальное имя файла лога для каждого чанка
            if (stripos(PHP_OS, 'WIN') === 0) {
                $currentLogFile = $projectDir . '\logs\generate-users-' . $chunk . '.log';
                $command = "cd /d {$projectDir} && {$php} {$yii} users/generate-users {$start} {$end} > {$currentLogFile} 2>&1";
            } else {
                $currentLogFile = $projectDir . '/logs/generate-users-' . $chunk . '.log';
                $command = "cd {$projectDir} && {$php} {$yii} users/generate-users {$start} {$end} > {$currentLogFile} 2>&1";
            }

            // Запускаем процесс с помощью proc_open
            $descriptorspec = [
                0 => ["pipe", "r"],
                1 => ["pipe", "w"],
                2 => ["pipe", "w"]
            ];

            $process = proc_open($command, $descriptorspec, $pipes);

            if (is_resource($process)) {
                $processes[] = ['process' => $process, 'pipes' => $pipes];
                echo "Чанк $chunk запущен...\n";
            } else {
                echo "Не удалось запустить чанк $chunk...\n";
            }
        }

        // Ждем завершения всех запущенных процессов
        while (count($processes) > 0) {
            foreach ($processes as $key => $procData) {
                $status = proc_get_status($procData['process']);
                if (!$status['running']) {
                    proc_close($procData['process']);
                    unset($processes[$key]);
                }
            }
            if (count($processes) > 0) {
                sleep(1);
            }
        }

        echo "Все чанки завершены!\n";
    }

    public function safeDown()
    {
        echo "Удаляем всех пользователей...\n";
        try {
            $this->delete('{{%user}}');
            $this->getDb()->createCommand('COMMIT')->execute();
        } catch (\Exception $e) {
            echo "Ошибка при удалении пользователей: " . $e->getMessage() . "\n";
        }
    }
}