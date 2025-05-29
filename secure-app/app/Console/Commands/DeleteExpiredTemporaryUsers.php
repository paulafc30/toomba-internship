<?php

namespace App\Console\Commands;

use App\Models\TemporaryLink;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteExpiredTemporaryUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'temporary:delete-expired-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes temporary users whose associated link has expired, but keeps the temporary link record.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Obtener todos los enlaces temporales que han expirado
        // y que tienen un user_id asociado (es decir, que crearon un usuario temporal).
        $expiredLinks = TemporaryLink::where('expires_at', '<=', now())
                                     ->whereNotNull('user_id')
                                     ->get();

        $count = 0;
        foreach ($expiredLinks as $link) {
            // Verificar si el usuario asociado aún existe antes de intentar eliminarlo
            if ($link->user) {
                User::where('id', $link->user_id)->delete();
                $count++;
                $this->info("Deleted user with ID: {$link->user_id} (email: {$link->email}) for expired link token: {$link->token}");
            } else {
                $this->warn("User with ID: {$link->user_id} for link token: {$link->token} not found or already deleted.");
            }
            // Importante: NO eliminar el $link->delete(); aquí, ya que el usuario quiere mantener los registros de enlaces temporales.
        }

        $this->info("Successfully processed. Deleted {$count} expired temporary users. Temporary link records remain.");
    }
}