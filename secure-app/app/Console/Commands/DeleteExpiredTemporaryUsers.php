<?php

namespace App\Console\Commands;

use App\Models\TemporaryLink;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class DeleteExpiredTemporaryUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'temporary-users:delete-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes temporary users whose associated links have expired.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $now = Carbon::now('UTC');

        $expiredLinks = TemporaryLink::where('expires_at', '<=', $now)->get();

        $deletedUserCount = 0;

        foreach ($expiredLinks as $link) {
            $temporaryUser = User::where('email', $link->email)
                ->where('user_type', 'temporary')
                ->first();

            if ($temporaryUser) {
                // Eliminar permisos asociados (si los hay)
                \App\Models\Permission::where('user_id', $temporaryUser->id)
                    ->whereNotNull('folder_id')
                    ->delete();

                // Eliminar el usuario temporal
                $temporaryUser->delete();
                $deletedUserCount++;

                // Opcional: Eliminar el enlace temporal expirado
                $link->delete();
            }
        }

        $this->info("Successfully deleted {$deletedUserCount} expired temporary users and their links.");

        return Command::SUCCESS;
    }
}