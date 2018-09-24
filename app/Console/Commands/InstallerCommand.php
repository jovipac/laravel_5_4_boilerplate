<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Intervention\Image\ImageServiceProviderLaravel5;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Process\Process;
use App\Traits\Seedable;
use DB;

class InstallerCommand extends Command
{
    use Seedable;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:installer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the Application';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function fire(Filesystem $filesystem)
    {
        return $this->handle($filesystem);
    }

    /**
     * Get the composer command for the environment.
     *
     * @return string
     */
    protected function findComposer()
    {
        if (file_exists(getcwd().'/composer.phar')) {
            return '"'.PHP_BINARY.'" '.getcwd().'/composer.phar';
        }

        return 'composer';
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->line('Let\'s install the App!');
        $this->line(' ');

        $this->call('config:cache');
        
        $this->info("Database configuration...");
        $dbName = $this->ask('Enter the database name');
        $dbUser = $this->ask('Enter the database user', 'root');
        $dbPassword = $this->ask('Enter the database password', false);
        if($dbPassword == false) {
            $dbPassword = '';
        }
 
        // http://laravel-tricks.com/tricks/change-the-env-dynamically
        $env_update = $this->changeEnv([
            'DB_DATABASE'   => $dbName,
            'DB_USERNAME'   => $dbUser,
            'DB_PASSWORD'   => $dbPassword
        ]);

        $this->info('Publishing the Voyager assets, database, and config files');

        // Publish only relevant resources on install
        $this->info('Publishing vendor packages...');
        $this->call('vendor:publish', ['--provider' => ImageServiceProviderLaravel5::class]);
        $this->line(' ');


        $this->info('Adding the storage symlink to your public folder');
        $this->call('storage:link');

        $this->info('Dumping the autoloaded files and reloading all new files');

        $composer = $this->findComposer();

        $process = new Process($composer.' dump-autoload');
        $process->setTimeout(null); //Setting timeout to null to prevent installation from stopping at a certain point in time
        $process->setWorkingDirectory(base_path())->run();

        if ( $env_update ) {

            $this->info('Migrating the database tables into your application');
            $this->call('migrate');

            $this->info('Seeding data into the database');
            $this->seed('VoyagerDatabaseSeeder');            
            $this->seed('DatabaseSeeder');
            $this->line(' ');
 
            $this->info('Installing npm packages and compiling assets...');
            system('npm install');
            $this->line('npm packages installed!');
            system('npm run dev');
            $this->line('Assests compiled as development environment! If you have in production you will have to run npm run prod!');
            $this->line(' ');
        
            $this->line('Successfully installed the Application! Enjoy');
        } else {
            $this->line('Error!');
        }
    }

    // http://laravel-tricks.com/tricks/change-the-env-dynamically
    protected function changeEnv($data = array()){
        if(count($data) > 0){
 
            $filenameEnv = (file_exists(base_path(".env"))) ? '.env' : '.env.example';
 
            // Read .env-file
            $env = file_get_contents(base_path() . '/'.$filenameEnv);
 
            // Split string on every " " and write into array
            $env = preg_split('/\s+/', $env);;
 
            // Loop through given data
            foreach((array)$data as $key => $value){
 
                // Loop through .env-data
                foreach($env as $env_key => $env_value){
 
                    // Turn the value into an array and stop after the first split
                    // So it's not possible to split e.g. the App-Key by accident
                    $entry = explode("=", $env_value, 2);
 
                    // Check, if new key fits the actual .env-key
                    if($entry[0] == $key){
                        // If yes, overwrite it with the new one
                        $env[$env_key] = $key . "=" . $value;
                    } else {
                        // If not, keep the old one
                        $env[$env_key] = $env_value;
                    }
                }
            }
 
            // Turn the array back to an String
            $env = implode("\n", $env);
 
            // And overwrite the .env with the new data
            file_put_contents(base_path() . '/'.$filenameEnv, $env);
            
            return true;
        } else {
            return false;
        }
    }    
}
