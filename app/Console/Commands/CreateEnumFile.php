<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateEnumFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:enum {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Laravel-Spatie-Data Enum';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $class_name = $this->argument('name');

        $fileContents = <<<EOT
            <?php

            namespace App\Enum;
            use OpenApi\Attributes as OAT;

            #[OAT\Schema]
            enum $class_name: int
            {
                case TestCase = 1;

                public function label(): string
                {
                    return match (\$this) {
                        self::TestCase => 'label1',
                    };
                }
            }

            EOT;

        $written = \Storage::disk('app')
            ->put('Enum'.'\\'.$this->argument('name').'.php', $fileContents);

        if ($written) {
            $this->info('Created new Repo '.$this->argument('name').'Repository.php in App\Repositories.');
        } else {
            $this->info('Something went wrong');
        }

    }
}
